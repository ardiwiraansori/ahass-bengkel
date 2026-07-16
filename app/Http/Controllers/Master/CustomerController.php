<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreCustomerRequest;
use App\Http\Requests\Master\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class CustomerController extends Controller
{
    public function index(): View
    {
        return view('master.customers.index');
    }

    public function data(): JsonResponse
    {
        $customers = Customer::query()
            ->withCount('vehicles')
            ->orderBy('nama_customer')
            ->get([
                'id_customer',
                'nama_customer',
                'no_hp',
                'email',
                'no_identitas',
                'alamat',
                'created_at',
                'updated_at',
            ]);

        return response()->json([
            'success' => true,
            'data' => $customers,
        ]);
    }

    public function store(
        StoreCustomerRequest $request
    ): JsonResponse {
        try {
            $validated = $request->validated();

            $customer = DB::transaction(function () use (
                $validated
            ) {
                $validated['id_customer'] =
                    $this->generateCustomerId();

                return Customer::create($validated);
            });

            return response()->json([
                'success' => true,
                'message' =>
                'Master customer berhasil ditambahkan.',
                'data' => $customer,
            ], 201);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' =>
                'Master customer gagal ditambahkan.',
            ], 500);
        }
    }

    public function show(Request $request): JsonResponse
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
            ->with([
                'vehicles' => function ($query) {
                    $query->orderBy('no_plat');
                },
            ])
            ->findOrFail($validated['id_customer']);

        return response()->json([
            'success' => true,
            'data' => $customer,
        ]);
    }

    public function update(
        UpdateCustomerRequest $request
    ): JsonResponse {
        try {
            $validated = $request->validated();

            $customerId = $validated['id_customer'];

            unset($validated['id_customer']);

            $customer = Customer::query()
                ->findOrFail($customerId);

            $customer->update($validated);

            return response()->json([
                'success' => true,
                'message' =>
                'Master customer berhasil diperbarui.',
                'data' => $customer->fresh(),
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' =>
                'Master customer gagal diperbarui.',
            ], 500);
        }
    }

    private function generateCustomerId(): string
    {
        $dealerId = preg_replace(
            '/[^A-Za-z0-9]/',
            '',
            (string) config(
                'services.dgi.dealer_id',
                '68601'
            )
        );

        $prefix = sprintf('CUST/%s/', $dealerId);

        $lastCustomerId = Customer::query()
            ->where(
                'id_customer',
                'like',
                $prefix . '%'
            )
            ->lockForUpdate()
            ->orderByDesc('id_customer')
            ->value('id_customer');

        $lastSequence = $lastCustomerId
            ? (int) Str::afterLast(
                $lastCustomerId,
                '/'
            )
            : 0;

        do {
            $lastSequence++;

            $customerId = $prefix . str_pad(
                (string) $lastSequence,
                5,
                '0',
                STR_PAD_LEFT
            );
        } while (
            Customer::query()
            ->whereKey($customerId)
            ->exists()
        );

        return $customerId;
    }
}
