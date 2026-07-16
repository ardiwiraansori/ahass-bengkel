<?php

namespace App\Http\Requests\Transaction;

use App\Models\WorkOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateWorkOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $mechanicId = trim(
            (string) $this->id_mekanik
        );

        $catatanMekanik = trim(
            (string) $this->catatan_mekanik
        );

        $diskon = preg_replace(
            '/[^0-9]/',
            '',
            (string) $this->diskon
        );

        $this->merge([
            'id_wo' => trim(
                (string) $this->id_wo
            ),

            'id_mekanik' => $mechanicId !== ''
                ? $mechanicId
                : null,

            'diskon' => $diskon !== ''
                ? $diskon
                : 0,

            'catatan_mekanik' => $catatanMekanik !== ''
                ? $catatanMekanik
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

            'id_mekanik' => [
                'nullable',
                'string',

                Rule::exists(
                    'mechanics',
                    'id_mekanik'
                )->where(function ($query) {
                    $query->where(
                        'status_aktif',
                        true
                    );
                }),
            ],

            'diskon' => [
                'required',
                'integer',
                'min:0',
                'max:999999999999',
            ],

            'catatan_mekanik' => [
                'nullable',
                'string',
                'max:2000',
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

            if (! $workOrder) {
                return;
            }

            if (! $workOrder->isEditable()) {
                $validator->errors()->add(
                    'id_wo',
                    'Work Order hanya dapat diedit ketika berstatus DRAFT atau MENUNGGU.'
                );
            }

            if (
                $workOrder->status ===
                WorkOrder::STATUS_MENUNGGU &&
                ! $this->input('id_mekanik')
            ) {
                $validator->errors()->add(
                    'id_mekanik',
                    'Work Order berstatus MENUNGGU harus memiliki mekanik.'
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

            'id_mekanik.exists' =>
            'Mekanik tidak ditemukan atau sedang nonaktif.',

            'diskon.required' =>
            'Diskon wajib diisi.',

            'diskon.integer' =>
            'Diskon harus berupa angka.',

            'diskon.min' =>
            'Diskon tidak boleh kurang dari nol.',

            'catatan_mekanik.max' =>
            'Catatan mekanik maksimal 2000 karakter.',
        ];
    }
}
