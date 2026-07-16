<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreVehicleRequest;
use App\Http\Requests\Master\UpdateVehicleRequest;
use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class VehicleController extends Controller
{
    public function data(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_customer' => [
                'required',
                'string',
                'exists:customers,id_customer',
            ],
        ], [
            'id_customer.required' =>
            'ID customer wajib dikirim.',

            'id_customer.exists' =>
            'Data customer tidak ditemukan.',
        ]);

        $customer = Customer::query()
            ->select([
                'id_customer',
                'nama_customer',
                'no_hp',
            ])
            ->findOrFail($validated['id_customer']);

        $vehicles = Vehicle::query()
            ->where(
                'id_customer',
                $customer->id_customer
            )
            ->orderBy('no_plat')
            ->get();

        return response()->json([
            'success' => true,

            'data' => [
                'customer' => $customer,
                'vehicles' => $vehicles,
            ],
        ]);
    }

    public function store(
        StoreVehicleRequest $request
    ): JsonResponse {
        try {
            $vehicle = Vehicle::create(
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' =>
                'Kendaraan berhasil ditambahkan.',
                'data' => $vehicle,
            ], 201);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' =>
                'Kendaraan gagal ditambahkan.',
            ], 500);
        }
    }

    public function show(
        Vehicle $vehicle
    ): JsonResponse {
        $vehicle->load([
            'customer:id_customer,nama_customer',
        ]);

        return response()->json([
            'success' => true,
            'data' => $vehicle,
        ]);
    }

    public function update(
        UpdateVehicleRequest $request,
        Vehicle $vehicle
    ): JsonResponse {
        try {
            $vehicle->update(
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' =>
                'Kendaraan berhasil diperbarui.',
                'data' => $vehicle->fresh(),
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' =>
                'Kendaraan gagal diperbarui.',
            ], 500);
        }
    }
}
