<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\StoreServiceAdvisorRequest;
use App\Http\Requests\Transaction\UpdateServiceAdvisorRequest;
use App\Models\ServiceAdvisorForm;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class ServiceAdvisorController extends Controller
{
    public function index(): View
    {
        return view(
            'transactions.service-advisors.index'
        );
    }

    public function data(): JsonResponse
    {
        $serviceAdvisorForms =
            ServiceAdvisorForm::query()
            ->with([
                'customer:id_customer,nama_customer,no_hp',

                'vehicle:id,id_customer,no_plat,kode_motor,nama_unit,tahun',

                'workOrder:id_wo,id_sa,status',
            ])
            ->orderByDesc('tanggal_kedatangan')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $serviceAdvisorForms,
        ]);
    }

    public function store(
        StoreServiceAdvisorRequest $request
    ): JsonResponse {
        try {
            $validated = $request->validated();

            $tanggalKedatangan = Carbon::parse(
                $validated['tanggal_kedatangan']
            );

            $serviceAdvisorForm =
                DB::transaction(function () use (
                    $validated,
                    $tanggalKedatangan
                ) {
                    $validated['id_sa'] =
                        $this->generateServiceAdvisorId(
                            (int) $tanggalKedatangan->format('Y')
                        );

                    $validated['tanggal_kedatangan'] =
                        $tanggalKedatangan;

                    $validated['status'] =
                        ServiceAdvisorForm::STATUS_OPEN;

                    return ServiceAdvisorForm::create(
                        $validated
                    );
                });

            $serviceAdvisorForm->load([
                'customer:id_customer,nama_customer,no_hp',

                'vehicle:id,id_customer,no_plat,kode_motor,nama_unit,tahun',
            ]);

            return response()->json([
                'success' => true,

                'message' =>
                'Form Service Advisor berhasil dibuat.',

                'data' => $serviceAdvisorForm,
            ], 201);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,

                'message' =>
                'Form Service Advisor gagal dibuat.',
            ], 500);
        }
    }

    public function show(
        Request $request
    ): JsonResponse {
        $validated = $request->validate([
            'id_sa' => [
                'required',
                'string',
                'exists:service_advisor_forms,id_sa',
            ],
        ], [
            'id_sa.required' =>
            'ID Form Service Advisor wajib dikirim.',

            'id_sa.exists' =>
            'Form Service Advisor tidak ditemukan.',
        ]);

        $serviceAdvisorForm =
            ServiceAdvisorForm::query()
            ->with([
                'customer:id_customer,nama_customer,no_hp,email,no_identitas,alamat',

                'vehicle:id,id_customer,no_plat,kode_motor,nama_unit,tahun,no_rangka,no_mesin',

                'workOrder:id_wo,id_sa,id_mekanik,status,grand_total',
            ])
            ->findOrFail($validated['id_sa']);

        return response()->json([
            'success' => true,
            'data' => $serviceAdvisorForm,
        ]);
    }

    public function update(
        UpdateServiceAdvisorRequest $request
    ): JsonResponse {
        try {
            $validated = $request->validated();

            $serviceAdvisorForm =
                DB::transaction(function () use (
                    $validated
                ) {
                    $idServiceAdvisor =
                        $validated['id_sa'];

                    unset($validated['id_sa']);

                    $lockedServiceAdvisorForm =
                        ServiceAdvisorForm::query()
                        ->whereKey(
                            $idServiceAdvisor
                        )
                        ->lockForUpdate()
                        ->firstOrFail();

                    if (
                        ! $lockedServiceAdvisorForm
                            ->isOpen()
                    ) {
                        throw ValidationException::withMessages([
                            'id_sa' =>
                            'Form Service Advisor hanya dapat diedit ketika berstatus OPEN.',
                        ]);
                    }

                    $validated['tanggal_kedatangan'] =
                        Carbon::parse(
                            $validated['tanggal_kedatangan']
                        );

                    $lockedServiceAdvisorForm->update(
                        $validated
                    );

                    return $lockedServiceAdvisorForm
                        ->fresh([
                            'customer',
                            'vehicle',
                            'workOrder',
                        ]);
                });

            return response()->json([
                'success' => true,

                'message' =>
                'Form Service Advisor berhasil diperbarui.',

                'data' => $serviceAdvisorForm,
            ]);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,

                'message' =>
                'Form Service Advisor gagal diperbarui.',
            ], 500);
        }
    }

    public function cancel(
        Request $request
    ): JsonResponse {
        $validated = $request->validate([
            'id_sa' => [
                'required',
                'string',
                'exists:service_advisor_forms,id_sa',
            ],
        ], [
            'id_sa.required' =>
            'ID Form Service Advisor wajib dikirim.',

            'id_sa.exists' =>
            'Form Service Advisor tidak ditemukan.',
        ]);

        try {
            $serviceAdvisorForm =
                DB::transaction(function () use (
                    $validated
                ) {
                    $lockedServiceAdvisorForm =
                        ServiceAdvisorForm::query()
                        ->whereKey(
                            $validated['id_sa']
                        )
                        ->lockForUpdate()
                        ->firstOrFail();

                    if (
                        ! $lockedServiceAdvisorForm
                            ->isOpen()
                    ) {
                        throw ValidationException::withMessages([
                            'id_sa' =>
                            'Hanya Form Service Advisor berstatus OPEN yang dapat dibatalkan.',
                        ]);
                    }

                    $lockedServiceAdvisorForm->update([
                        'status' =>
                        ServiceAdvisorForm::STATUS_CANCELLED,
                    ]);

                    return $lockedServiceAdvisorForm
                        ->fresh();
                });

            return response()->json([
                'success' => true,

                'message' =>
                'Form Service Advisor berhasil dibatalkan.',

                'data' => $serviceAdvisorForm,
            ]);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,

                'message' =>
                'Form Service Advisor gagal dibatalkan.',
            ], 500);
        }
    }

    private function generateServiceAdvisorId(
        int $year
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
            'SA/%d/%s/',
            $year,
            $dealerId
        );

        $lastServiceAdvisorId =
            ServiceAdvisorForm::query()
            ->where(
                'id_sa',
                'like',
                $prefix . '%'
            )
            ->lockForUpdate()
            ->orderByDesc('id_sa')
            ->value('id_sa');

        $lastSequence =
            $lastServiceAdvisorId
            ? (int) Str::afterLast(
                $lastServiceAdvisorId,
                '/'
            )
            : 0;

        do {
            $lastSequence++;

            $serviceAdvisorId =
                $prefix . str_pad(
                    (string) $lastSequence,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
        } while (
            ServiceAdvisorForm::query()
            ->whereKey($serviceAdvisorId)
            ->exists()
        );

        return $serviceAdvisorId;
    }
}
