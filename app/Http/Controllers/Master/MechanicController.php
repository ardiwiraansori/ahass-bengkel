<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreMechanicRequest;
use App\Http\Requests\Master\UpdateMechanicRequest;
use App\Models\Mechanic;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Throwable;

class MechanicController extends Controller
{
    public function index(): View
    {
        return view('master.mechanics.index');
    }

    public function data(): JsonResponse
    {
        $mechanics = Mechanic::query()
            ->select([
                'id_mekanik',
                'honda_id_mekanik',
                'nama_mekanik',
                'no_hp',
                'status_aktif',
                'created_at',
                'updated_at',
            ])
            ->orderByDesc('status_aktif')
            ->orderBy('nama_mekanik')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $mechanics,
        ]);
    }

    public function store(
        StoreMechanicRequest $request
    ): JsonResponse {
        try {
            $mechanic = Mechanic::create(
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' =>
                'Master mekanik berhasil ditambahkan.',
                'data' => $mechanic,
            ], 201);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' =>
                'Master mekanik gagal ditambahkan.',
            ], 500);
        }
    }

    public function show(
        Mechanic $mechanic
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'data' => $mechanic,
        ]);
    }

    public function update(
        UpdateMechanicRequest $request,
        Mechanic $mechanic
    ): JsonResponse {
        try {
            $mechanic->update(
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' =>
                'Master mekanik berhasil diperbarui.',
                'data' => $mechanic->fresh(),
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' =>
                'Master mekanik gagal diperbarui.',
            ], 500);
        }
    }

    public function toggleStatus(
        Mechanic $mechanic
    ): JsonResponse {
        try {
            $mechanic->update([
                'status_aktif' =>
                ! $mechanic->status_aktif,
            ]);

            $mechanic->refresh();

            return response()->json([
                'success' => true,
                'message' => $mechanic->status_aktif
                    ? 'Mekanik berhasil diaktifkan.'
                    : 'Mekanik berhasil dinonaktifkan.',
                'data' => $mechanic,
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' =>
                'Status mekanik gagal diubah.',
            ], 500);
        }
    }
}
