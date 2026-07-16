<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StorePartRequest;
use App\Http\Requests\Master\UpdatePartRequest;
use App\Models\Part;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class PartController extends Controller
{
    public function index(): View
    {
        return view('master.parts.index');
    }

    public function data(): JsonResponse
    {
        $parts = Part::query()
            ->select([
                'part_number',
                'nama_part',
                'harga',
                'qty_stock',
                'qty_rfs',
                'qty_book',
                'is_active',
                'created_at',
                'updated_at',
            ])
            ->orderBy('nama_part')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $parts,
        ]);
    }

    public function store(StorePartRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $part = DB::transaction(function () use ($validated) {
                return Part::create([
                    'part_number' => $validated['part_number'],
                    'nama_part' => $validated['nama_part'],
                    'harga' => $validated['harga'],
                    'qty_stock' => $validated['qty_stock'],
                    'qty_rfs' => $validated['qty_stock'],
                    'qty_book' => 0,
                    'is_active' => $validated['is_active'],
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Master part berhasil ditambahkan.',
                'data' => $part,
            ], 201);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Master part gagal ditambahkan.',
            ], 500);
        }
    }

    public function show(Part $part): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $part,
        ]);
    }

    public function update(
        UpdatePartRequest $request,
        Part $part
    ): JsonResponse {
        try {
            $validated = $request->validated();

            $updatedPart = DB::transaction(function () use ($part, $validated) {
                $lockedPart = Part::query()
                    ->where('part_number', $part->part_number)
                    ->lockForUpdate()
                    ->firstOrFail();

                $qtyStockBaru = (int) $validated['qty_stock'];
                $qtyBook = (int) $lockedPart->qty_book;

                if ($qtyStockBaru < $qtyBook) {
                    throw ValidationException::withMessages([
                        'qty_stock' => sprintf(
                            'Qty Stock tidak boleh lebih kecil dari Qty Book (%d).',
                            $qtyBook
                        ),
                    ]);
                }

                $lockedPart->update([
                    'nama_part' => $validated['nama_part'],
                    'harga' => $validated['harga'],
                    'qty_stock' => $qtyStockBaru,
                    'qty_rfs' => $qtyStockBaru - $qtyBook,
                    'is_active' => $validated['is_active'],
                ]);

                return $lockedPart->fresh();
            });

            return response()->json([
                'success' => true,
                'message' => 'Master part berhasil diperbarui.',
                'data' => $updatedPart,
            ]);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Master part gagal diperbarui.',
            ], 500);
        }
    }

    public function toggleStatus(Part $part): JsonResponse
    {
        try {
            $part->update([
                'is_active' => ! $part->is_active,
            ]);

            return response()->json([
                'success' => true,
                'message' => $part->is_active
                    ? 'Master part berhasil diaktifkan.'
                    : 'Master part berhasil dinonaktifkan.',
                'data' => $part->fresh(),
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Status master part gagal diubah.',
            ], 500);
        }
    }
}
