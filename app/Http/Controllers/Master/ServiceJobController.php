<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreServiceJobRequest;
use App\Http\Requests\Master\UpdateServiceJobRequest;
use App\Models\ServiceJob;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Throwable;

class ServiceJobController extends Controller
{
    public function index(): View
    {
        return view('master.jobs.index');
    }

    public function data(): JsonResponse
    {
        $jobs = ServiceJob::query()
            ->select([
                'id_job',
                'kode_motor',
                'keterangan',
                'harga',
                'is_active',
                'created_at',
                'updated_at',
            ])
            ->orderBy('kode_motor')
            ->orderBy('keterangan')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $jobs,
        ]);
    }

    public function store(StoreServiceJobRequest $request): JsonResponse
    {
        try {
            $serviceJob = ServiceJob::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Master jasa berhasil ditambahkan.',
                'data' => $serviceJob,
            ], 201);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Master jasa gagal ditambahkan.',
            ], 500);
        }
    }

    public function show(ServiceJob $serviceJob): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $serviceJob,
        ]);
    }

    public function update(
        UpdateServiceJobRequest $request,
        ServiceJob $serviceJob
    ): JsonResponse {
        try {
            $serviceJob->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Master jasa berhasil diperbarui.',
                'data' => $serviceJob->fresh(),
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Master jasa gagal diperbarui.',
            ], 500);
        }
    }

    public function toggleStatus(ServiceJob $serviceJob): JsonResponse
    {
        try {
            $serviceJob->update([
                'is_active' => ! $serviceJob->is_active,
            ]);

            return response()->json([
                'success' => true,
                'message' => $serviceJob->is_active
                    ? 'Master jasa berhasil diaktifkan.'
                    : 'Master jasa berhasil dinonaktifkan.',
                'data' => $serviceJob->fresh(),
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Status master jasa gagal diubah.',
            ], 500);
        }
    }
}
