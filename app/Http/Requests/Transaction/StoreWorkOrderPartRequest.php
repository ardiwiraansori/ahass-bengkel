<?php

namespace App\Http\Requests\Transaction;

use App\Models\WorkOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreWorkOrderPartRequest extends FormRequest
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
            'id_wo' => trim(
                (string) $this->id_wo
            ),

            'part_number' => strtoupper(
                trim((string) $this->part_number)
            ),

            'qty' => $qty !== ''
                ? $qty
                : null,
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

            'part_number' => [
                'required',
                'string',

                Rule::exists(
                    'master_parts',
                    'part_number'
                )->where(function ($query) {
                    $query->where(
                        'is_active',
                        true
                    );
                }),

                Rule::unique(
                    'work_order_parts',
                    'part_number'
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
                'max:999999',
            ],
        ];
    }

    public function withValidator(
        Validator $validator
    ): void {
        $validator->after(function (
            Validator $validator
        ) {
            if (
                $validator->errors()->has('id_wo')
            ) {
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
                    'Part hanya dapat ditambahkan ketika Work Order berstatus DRAFT atau MENUNGGU.'
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

            'part_number.required' =>
            'Part wajib dipilih.',

            'part_number.exists' =>
            'Part tidak ditemukan atau sedang nonaktif.',

            'part_number.unique' =>
            'Part tersebut sudah ada di Work Order.',

            'qty.required' =>
            'Jumlah part wajib diisi.',

            'qty.integer' =>
            'Jumlah part harus berupa bilangan bulat.',

            'qty.min' =>
            'Jumlah part minimal satu.',

            'qty.max' =>
            'Jumlah part terlalu besar.',
        ];
    }
}
