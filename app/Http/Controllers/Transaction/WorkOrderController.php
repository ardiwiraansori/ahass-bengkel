<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\StoreWorkOrderRequest;
use App\Http\Requests\Transaction\UpdateWorkOrderRequest;
use App\Models\ServiceAdvisorForm;
use App\Models\WorkOrder;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class WorkOrderController extends Controller
{
    public function index(): View
    {
        return view(
            'transactions.work-orders.index'
        );
    }

    public function data(): JsonResponse
    {
        $workOrders = WorkOrder::query()
            ->with([
                'serviceAdvisorForm:id_sa,id_customer,vehicle_id,tanggal_kedatangan,kilometer,keluhan,status',

                'serviceAdvisorForm.customer:id_customer,nama_customer,no_hp',

                'serviceAdvisorForm.vehicle:id,id_customer,no_plat,kode_motor,nama_unit,tahun',

                'mechanic:id_mekanik,honda_id_mekanik,nama_mekanik,status_aktif',
            ])
            ->withCount([
                'jobs',
                'parts',
            ])
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $workOrders,
        ]);
    }

    public function store(
        StoreWorkOrderRequest $request
    ): JsonResponse {
        try {
            $validated = $request->validated();

            $workOrder = DB::transaction(function () use (
                $validated
            ) {
                $serviceAdvisorForm =
                    ServiceAdvisorForm::query()
                    ->whereKey($validated['id_sa'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! $serviceAdvisorForm->isOpen()) {
                    throw ValidationException::withMessages([
                        'id_sa' =>
                        'Hanya Form Service Advisor berstatus OPEN yang dapat dibuatkan Work Order.',
                    ]);
                }

                if (
                    WorkOrder::query()
                    ->where(
                        'id_sa',
                        $serviceAdvisorForm->id_sa
                    )
                    ->exists()
                ) {
                    throw ValidationException::withMessages([
                        'id_sa' =>
                        'Form Service Advisor ini sudah memiliki Work Order.',
                    ]);
                }

                $createdAt = now();

                $workOrder = WorkOrder::create([
                    'id_wo' =>
                    $this->generateWorkOrderId(
                        $createdAt
                    ),

                    'id_sa' =>
                    $serviceAdvisorForm->id_sa,

                    'id_mekanik' =>
                    $validated['id_mekanik'],

                    'status' =>
                    WorkOrder::STATUS_DRAFT,

                    'total_jasa' => 0,
                    'total_part' => 0,
                    'diskon' => 0,
                    'grand_total' => 0,

                    'jumlah_bayar' => 0,
                    'kembalian' => 0,

                    'catatan_mekanik' =>
                    $validated['catatan_mekanik'],

                    'dgi_status' =>
                    WorkOrder::DGI_PENDING,
                ]);

                $serviceAdvisorForm->update([
                    'status' =>
                    ServiceAdvisorForm::STATUS_CONVERTED,
                ]);

                return $workOrder;
            });

            $workOrder->load([
                'serviceAdvisorForm.customer',
                'serviceAdvisorForm.vehicle',
                'mechanic',
            ]);

            return response()->json([
                'success' => true,

                'message' =>
                'Work Order berhasil dibuat.',

                'data' => $workOrder,
            ], 201);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,

                'message' =>
                'Work Order gagal dibuat.',
            ], 500);
        }
    }

    public function show(
        Request $request
    ): JsonResponse {
        $validated = $request->validate([
            'id_wo' => [
                'required',
                'string',
                'exists:work_orders,id_wo',
            ],
        ], [
            'id_wo.required' =>
            'ID Work Order wajib dikirim.',

            'id_wo.exists' =>
            'Work Order tidak ditemukan.',
        ]);

        $workOrder = WorkOrder::query()
            ->with([
                'serviceAdvisorForm.customer',

                'serviceAdvisorForm.vehicle',

                'mechanic',

                'jobs' => function ($query) {
                    $query->orderBy('id');
                },

                'jobs.serviceJob',

                'parts' => function ($query) {
                    $query->orderBy('id');
                },

                'parts.part',
            ])
            ->findOrFail($validated['id_wo']);

        return response()->json([
            'success' => true,
            'data' => $workOrder,
        ]);
    }

    public function update(
        UpdateWorkOrderRequest $request
    ): JsonResponse {
        try {
            $validated = $request->validated();

            $workOrder = DB::transaction(function () use (
                $validated
            ) {
                $lockedWorkOrder = WorkOrder::query()
                    ->whereKey($validated['id_wo'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! $lockedWorkOrder->isEditable()) {
                    throw ValidationException::withMessages([
                        'id_wo' =>
                        'Work Order hanya dapat diedit ketika berstatus DRAFT atau MENUNGGU.',
                    ]);
                }

                if (
                    $lockedWorkOrder->status ===
                    WorkOrder::STATUS_MENUNGGU &&
                    ! $validated['id_mekanik']
                ) {
                    throw ValidationException::withMessages([
                        'id_mekanik' =>
                        'Work Order berstatus MENUNGGU harus memiliki mekanik.',
                    ]);
                }

                $lockedWorkOrder->update([
                    'id_mekanik' =>
                    $validated['id_mekanik'],

                    'diskon' =>
                    $validated['diskon'],

                    'catatan_mekanik' =>
                    $validated['catatan_mekanik'],
                ]);

                $lockedWorkOrder
                    ->recalculateTotals();

                return $lockedWorkOrder->fresh([
                    'serviceAdvisorForm.customer',
                    'serviceAdvisorForm.vehicle',
                    'mechanic',
                    'jobs',
                    'parts',
                ]);
            });

            return response()->json([
                'success' => true,

                'message' =>
                'Work Order berhasil diperbarui.',

                'data' => $workOrder,
            ]);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,

                'message' =>
                'Work Order gagal diperbarui.',
            ], 500);
        }
    }

    private function generateWorkOrderId(
        Carbon $date
    ): string {
        $dealerId = preg_replace(
            '/[^A-Za-z0-9]/',
            '',
            (string) config(
                'services.dgi.dealer_id',
                '68601'
            )
        );

        $prefix = sprintf(
            'WO/%s/%s/%s/',
            $date->format('y'),
            $date->format('m'),
            $dealerId
        );

        $lastWorkOrderId = WorkOrder::query()
            ->where(
                'id_wo',
                'like',
                $prefix . '%'
            )
            ->lockForUpdate()
            ->orderByDesc('id_wo')
            ->value('id_wo');

        $lastSequence = $lastWorkOrderId
            ? (int) Str::afterLast(
                $lastWorkOrderId,
                '/'
            )
            : 0;

        do {
            $lastSequence++;

            $workOrderId =
                $prefix . str_pad(
                    (string) $lastSequence,
                    5,
                    '0',
                    STR_PAD_LEFT
                );
        } while (
            WorkOrder::query()
            ->whereKey($workOrderId)
            ->exists()
        );

        return $workOrderId;
    }
}
