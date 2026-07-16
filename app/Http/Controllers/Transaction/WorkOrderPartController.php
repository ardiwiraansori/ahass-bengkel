<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\StoreWorkOrderPartRequest;
use App\Http\Requests\Transaction\UpdateWorkOrderPartRequest;
use App\Models\Part;
use App\Models\WorkOrder;
use App\Models\WorkOrderPart;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class WorkOrderPartController extends Controller
{
    public function store(
        StoreWorkOrderPartRequest $request
    ): JsonResponse {
        try {
            $validated = $request->validated();

            $result = DB::transaction(function () use (
                $validated
            ) {
                /*
                 * Kunci Work Order agar status tidak berubah
                 * ketika reservasi stok sedang dilakukan.
                 */
                $workOrder = WorkOrder::query()
                    ->whereKey($validated['id_wo'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! $workOrder->isEditable()) {
                    throw ValidationException::withMessages([
                        'id_wo' =>
                        'Part hanya dapat ditambahkan ketika Work Order berstatus DRAFT atau MENUNGGU.',
                    ]);
                }

                /*
                 * Kunci Master Part agar reservasi stok aman
                 * dari transaksi bersamaan.
                 */
                $part = Part::query()
                    ->whereKey(
                        $validated['part_number']
                    )
                    ->lockForUpdate()
                    ->first();

                if (
                    ! $part ||
                    ! $part->is_active
                ) {
                    throw ValidationException::withMessages([
                        'part_number' =>
                        'Part tidak ditemukan atau sedang nonaktif.',
                    ]);
                }

                $alreadyExists =
                    WorkOrderPart::query()
                    ->where(
                        'id_wo',
                        $workOrder->id_wo
                    )
                    ->where(
                        'part_number',
                        $part->part_number
                    )
                    ->exists();

                if ($alreadyExists) {
                    throw ValidationException::withMessages([
                        'part_number' =>
                        'Part tersebut sudah ada di Work Order.',
                    ]);
                }

                $qty = (int) $validated['qty'];

                if (
                    (int) $part->qty_rfs < $qty
                ) {
                    throw ValidationException::withMessages([
                        'qty' => sprintf(
                            'Stok RFS tidak mencukupi. Stok yang dapat digunakan hanya %d.',
                            (int) $part->qty_rfs
                        ),
                    ]);
                }

                $hargaSatuan =
                    (int) $part->harga;

                /*
                 * Simpan snapshot nama dan harga part.
                 */
                $workOrderPart =
                    WorkOrderPart::create([
                        'id_wo' =>
                        $workOrder->id_wo,

                        'part_number' =>
                        $part->part_number,

                        'nama_part' =>
                        $part->nama_part,

                        'qty' => $qty,

                        'harga_satuan' =>
                        $hargaSatuan,

                        'subtotal' =>
                        WorkOrderPart::calculateSubtotal(
                            $qty,
                            $hargaSatuan
                        ),
                    ]);

                /*
                 * Reservasi stok:
                 *
                 * RFS  berkurang
                 * Book bertambah
                 * Stock fisik belum berubah
                 */
                $part->update([
                    'qty_rfs' =>
                    (int) $part->qty_rfs -
                        $qty,

                    'qty_book' =>
                    (int) $part->qty_book +
                        $qty,
                ]);

                $workOrder->recalculateTotals();

                return [
                    'part' =>
                    $workOrderPart->fresh([
                        'part',
                    ]),

                    'master_part' =>
                    $part->fresh(),

                    'work_order' =>
                    $workOrder->fresh(),
                ];
            });

            return response()->json([
                'success' => true,

                'message' =>
                'Part berhasil ditambahkan dan stok berhasil direservasi.',

                'data' => $result,
            ], 201);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,

                'message' =>
                'Part gagal ditambahkan ke Work Order.',
            ], 500);
        }
    }

    public function update(
        UpdateWorkOrderPartRequest $request,
        WorkOrderPart $workOrderPart
    ): JsonResponse {
        try {
            $validated = $request->validated();

            $result = DB::transaction(function () use (
                $validated,
                $workOrderPart
            ) {
                /*
                 * Ambil ulang data detail dengan lock.
                 */
                $lockedWorkOrderPart =
                    WorkOrderPart::query()
                    ->whereKey(
                        $workOrderPart->getKey()
                    )
                    ->lockForUpdate()
                    ->firstOrFail();

                $workOrder = WorkOrder::query()
                    ->whereKey(
                        $lockedWorkOrderPart->id_wo
                    )
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! $workOrder->isEditable()) {
                    throw ValidationException::withMessages([
                        'qty' =>
                        'Part hanya dapat diubah ketika Work Order berstatus DRAFT atau MENUNGGU.',
                    ]);
                }

                $part = Part::query()
                    ->whereKey(
                        $lockedWorkOrderPart
                            ->part_number
                    )
                    ->lockForUpdate()
                    ->firstOrFail();

                $oldQty =
                    (int) $lockedWorkOrderPart->qty;

                $newQty =
                    (int) $validated['qty'];

                $difference =
                    $newQty - $oldQty;

                /*
                 * Qty dinaikkan:
                 * ambil selisih dari RFS dan tambahkan Book.
                 */
                if ($difference > 0) {
                    if (
                        (int) $part->qty_rfs <
                        $difference
                    ) {
                        throw ValidationException::withMessages([
                            'qty' => sprintf(
                                'Stok RFS tidak mencukupi. Tambahan maksimal %d sehingga jumlah maksimal menjadi %d.',
                                (int) $part->qty_rfs,
                                $oldQty +
                                    (int) $part->qty_rfs
                            ),
                        ]);
                    }

                    $part->update([
                        'qty_rfs' =>
                        (int) $part->qty_rfs -
                            $difference,

                        'qty_book' =>
                        (int) $part->qty_book +
                            $difference,
                    ]);
                }

                /*
                 * Qty diturunkan:
                 * kembalikan selisih ke RFS dan kurangi Book.
                 */
                if ($difference < 0) {
                    $releasedQty =
                        abs($difference);

                    if (
                        (int) $part->qty_book <
                        $releasedQty
                    ) {
                        throw ValidationException::withMessages([
                            'qty' =>
                            'Data reservasi stok tidak konsisten. Qty Book lebih kecil dari stok yang akan dilepas.',
                        ]);
                    }

                    $part->update([
                        'qty_rfs' =>
                        (int) $part->qty_rfs +
                            $releasedQty,

                        'qty_book' =>
                        (int) $part->qty_book -
                            $releasedQty,
                    ]);
                }

                /*
                 * Harga tetap menggunakan snapshot transaksi.
                 */
                $lockedWorkOrderPart->update([
                    'qty' => $newQty,

                    'subtotal' =>
                    WorkOrderPart::calculateSubtotal(
                        $newQty,
                        (int) $lockedWorkOrderPart
                            ->harga_satuan
                    ),
                ]);

                $workOrder->recalculateTotals();

                return [
                    'part' =>
                    $lockedWorkOrderPart
                        ->fresh([
                            'part',
                        ]),

                    'master_part' =>
                    $part->fresh(),

                    'work_order' =>
                    $workOrder->fresh(),
                ];
            });

            return response()->json([
                'success' => true,

                'message' =>
                'Jumlah part dan reservasi stok berhasil diperbarui.',

                'data' => $result,
            ]);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,

                'message' =>
                'Part Work Order gagal diperbarui.',
            ], 500);
        }
    }

    public function destroy(
        WorkOrderPart $workOrderPart
    ): JsonResponse {
        try {
            $result = DB::transaction(function () use (
                $workOrderPart
            ) {
                $lockedWorkOrderPart =
                    WorkOrderPart::query()
                    ->whereKey(
                        $workOrderPart->getKey()
                    )
                    ->lockForUpdate()
                    ->firstOrFail();

                $workOrder = WorkOrder::query()
                    ->whereKey(
                        $lockedWorkOrderPart->id_wo
                    )
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! $workOrder->isEditable()) {
                    throw ValidationException::withMessages([
                        'part' =>
                        'Part hanya dapat dihapus ketika Work Order berstatus DRAFT atau MENUNGGU.',
                    ]);
                }

                $part = Part::query()
                    ->whereKey(
                        $lockedWorkOrderPart
                            ->part_number
                    )
                    ->lockForUpdate()
                    ->firstOrFail();

                $reservedQty =
                    (int) $lockedWorkOrderPart->qty;

                if (
                    (int) $part->qty_book <
                    $reservedQty
                ) {
                    throw ValidationException::withMessages([
                        'part' =>
                        'Data reservasi stok tidak konsisten. Qty Book lebih kecil dari jumlah part Work Order.',
                    ]);
                }

                /*
                 * Lepaskan seluruh reservasi:
                 *
                 * RFS  bertambah
                 * Book berkurang
                 * Stock fisik tetap
                 */
                $part->update([
                    'qty_rfs' =>
                    (int) $part->qty_rfs +
                        $reservedQty,

                    'qty_book' =>
                    (int) $part->qty_book -
                        $reservedQty,
                ]);

                $lockedWorkOrderPart->delete();

                $workOrder->recalculateTotals();

                return [
                    'master_part' =>
                    $part->fresh(),

                    'work_order' =>
                    $workOrder->fresh(),
                ];
            });

            return response()->json([
                'success' => true,

                'message' =>
                'Part berhasil dihapus dan reservasi stok dikembalikan.',

                'data' => $result,
            ]);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,

                'message' =>
                'Part Work Order gagal dihapus.',
            ], 500);
        }
    }
}
