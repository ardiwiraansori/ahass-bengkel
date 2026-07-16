<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\StoreWorkOrderJobRequest;
use App\Http\Requests\Transaction\UpdateWorkOrderJobRequest;
use App\Models\ServiceJob;
use App\Models\WorkOrder;
use App\Models\WorkOrderJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class WorkOrderJobController extends Controller
{
    public function store(
        StoreWorkOrderJobRequest $request
    ): JsonResponse {
        try {
            $validated = $request->validated();

            $result = DB::transaction(function () use (
                $validated
            ) {
                $workOrder = WorkOrder::query()
                    ->whereKey($validated['id_wo'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! $workOrder->isEditable()) {
                    throw ValidationException::withMessages([
                        'id_wo' =>
                        'Jasa hanya dapat ditambahkan ketika Work Order berstatus DRAFT atau MENUNGGU.',
                    ]);
                }

                $serviceJob = ServiceJob::query()
                    ->whereKey($validated['id_job'])
                    ->where('is_active', true)
                    ->first();

                if (! $serviceJob) {
                    throw ValidationException::withMessages([
                        'id_job' =>
                        'Jasa tidak ditemukan atau sedang nonaktif.',
                    ]);
                }

                $alreadyExists =
                    WorkOrderJob::query()
                    ->where(
                        'id_wo',
                        $workOrder->id_wo
                    )
                    ->where(
                        'id_job',
                        $serviceJob->id_job
                    )
                    ->exists();

                if ($alreadyExists) {
                    throw ValidationException::withMessages([
                        'id_job' =>
                        'Jasa tersebut sudah ada di Work Order.',
                    ]);
                }

                $qty = (int) $validated['qty'];
                $hargaSatuan =
                    (int) $serviceJob->harga;

                $workOrderJob =
                    WorkOrderJob::create([
                        'id_wo' =>
                        $workOrder->id_wo,

                        'id_job' =>
                        $serviceJob->id_job,

                        'keterangan_job' =>
                        $serviceJob->keterangan,

                        'qty' => $qty,

                        'harga_satuan' =>
                        $hargaSatuan,

                        'subtotal' =>
                        WorkOrderJob::calculateSubtotal(
                            $qty,
                            $hargaSatuan
                        ),
                    ]);

                $workOrder->recalculateTotals();

                return [
                    'job' => $workOrderJob->fresh([
                        'serviceJob',
                    ]),

                    'work_order' =>
                    $workOrder->fresh(),
                ];
            });

            return response()->json([
                'success' => true,

                'message' =>
                'Jasa berhasil ditambahkan ke Work Order.',

                'data' => $result,
            ], 201);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,

                'message' =>
                'Jasa gagal ditambahkan ke Work Order.',
            ], 500);
        }
    }

    public function update(
        UpdateWorkOrderJobRequest $request,
        WorkOrderJob $workOrderJob
    ): JsonResponse {
        try {
            $validated = $request->validated();

            $result = DB::transaction(function () use (
                $validated,
                $workOrderJob
            ) {
                $lockedJob = WorkOrderJob::query()
                    ->whereKey(
                        $workOrderJob->getKey()
                    )
                    ->lockForUpdate()
                    ->firstOrFail();

                $workOrder = WorkOrder::query()
                    ->whereKey($lockedJob->id_wo)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! $workOrder->isEditable()) {
                    throw ValidationException::withMessages([
                        'qty' =>
                        'Jasa hanya dapat diubah ketika Work Order berstatus DRAFT atau MENUNGGU.',
                    ]);
                }

                $qty = (int) $validated['qty'];

                /*
                 * Harga menggunakan snapshot transaksi,
                 * bukan mengambil ulang harga master.
                 */
                $lockedJob->update([
                    'qty' => $qty,

                    'subtotal' =>
                    WorkOrderJob::calculateSubtotal(
                        $qty,
                        (int) $lockedJob
                            ->harga_satuan
                    ),
                ]);

                $workOrder->recalculateTotals();

                return [
                    'job' => $lockedJob->fresh([
                        'serviceJob',
                    ]),

                    'work_order' =>
                    $workOrder->fresh(),
                ];
            });

            return response()->json([
                'success' => true,

                'message' =>
                'Jumlah jasa berhasil diperbarui.',

                'data' => $result,
            ]);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,

                'message' =>
                'Jasa Work Order gagal diperbarui.',
            ], 500);
        }
    }

    public function destroy(
        WorkOrderJob $workOrderJob
    ): JsonResponse {
        try {
            $result = DB::transaction(function () use (
                $workOrderJob
            ) {
                $lockedJob = WorkOrderJob::query()
                    ->whereKey(
                        $workOrderJob->getKey()
                    )
                    ->lockForUpdate()
                    ->firstOrFail();

                $workOrder = WorkOrder::query()
                    ->whereKey($lockedJob->id_wo)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! $workOrder->isEditable()) {
                    throw ValidationException::withMessages([
                        'job' =>
                        'Jasa hanya dapat dihapus ketika Work Order berstatus DRAFT atau MENUNGGU.',
                    ]);
                }

                $lockedJob->delete();

                $workOrder->recalculateTotals();

                return [
                    'work_order' =>
                    $workOrder->fresh(),
                ];
            });

            return response()->json([
                'success' => true,

                'message' =>
                'Jasa berhasil dihapus dari Work Order.',

                'data' => $result,
            ]);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,

                'message' =>
                'Jasa Work Order gagal dihapus.',
            ], 500);
        }
    }
}
