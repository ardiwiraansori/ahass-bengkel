<?php

namespace App\Http\Requests\Transaction;

use App\Models\WorkOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreWorkOrderJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $qty = preg_replace(
            '/[^0-9]/',
            '',
            (string) $this->qty
        );

        $this->merge([
            'id_wo' => trim((string) $this->id_wo),

            'id_job' => strtoupper(
                trim((string) $this->id_job)
            ),

            'qty' => $qty !== '' ? $qty : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'id_wo' => [
                'required',
                'string',
                'exists:work_orders,id_wo',
            ],

            'id_job' => [
                'required',
                'string',

                Rule::exists(
                    'master_jobs',
                    'id_job'
                )->where(function ($query) {
                    $query->where('is_active', true);
                }),

                Rule::unique(
                    'work_order_jobs',
                    'id_job'
                )->where(function ($query) {
                    $query->where(
                        'id_wo',
                        $this->input('id_wo')
                    );
                }),
            ],

            'qty' => [
                'required',
                'integer',
                'min:1',
                'max:999',
            ],
        ];
    }

    public function withValidator(
        Validator $validator
    ): void {
        $validator->after(function (
            Validator $validator
        ) {
            if ($validator->errors()->has('id_wo')) {
                return;
            }

            $workOrder = WorkOrder::query()
                ->find($this->input('id_wo'));

            if (
                $workOrder &&
                ! $workOrder->isEditable()
            ) {
                $validator->errors()->add(
                    'id_wo',
                    'Jasa hanya dapat ditambahkan ketika Work Order berstatus DRAFT atau MENUNGGU.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'id_wo.required' =>
            'ID Work Order wajib dikirim.',

            'id_wo.exists' =>
            'Work Order tidak ditemukan.',

            'id_job.required' =>
            'Jasa wajib dipilih.',

            'id_job.exists' =>
            'Jasa tidak ditemukan atau sedang nonaktif.',

            'id_job.unique' =>
            'Jasa tersebut sudah ada di Work Order.',

            'qty.required' =>
            'Jumlah jasa wajib diisi.',

            'qty.integer' =>
            'Jumlah jasa harus berupa bilangan bulat.',

            'qty.min' =>
            'Jumlah jasa minimal satu.',

            'qty.max' =>
            'Jumlah jasa maksimal 999.',
        ];
    }
}
