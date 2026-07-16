<?php

namespace App\Http\Requests\Transaction;

use App\Models\ServiceAdvisorForm;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreWorkOrderRequest extends FormRequest
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

        $this->merge([
            'id_sa' => trim(
                (string) $this->id_sa
            ),

            'id_mekanik' => $mechanicId !== ''
                ? $mechanicId
                : null,

            'catatan_mekanik' => $catatanMekanik !== ''
                ? $catatanMekanik
                : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'id_sa' => [
                'required',
                'string',
                'exists:service_advisor_forms,id_sa',
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
                $validator->errors()->has('id_sa')
            ) {
                return;
            }

            $serviceAdvisorForm =
                ServiceAdvisorForm::query()
                ->with('workOrder')
                ->find($this->input('id_sa'));

            if (! $serviceAdvisorForm) {
                return;
            }

            if (! $serviceAdvisorForm->isOpen()) {
                $validator->errors()->add(
                    'id_sa',
                    'Hanya Form Service Advisor berstatus OPEN yang dapat dibuatkan Work Order.'
                );
            }

            if ($serviceAdvisorForm->workOrder) {
                $validator->errors()->add(
                    'id_sa',
                    'Form Service Advisor ini sudah memiliki Work Order.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'id_sa.required' =>
            'Form Service Advisor wajib dipilih.',

            'id_sa.exists' =>
            'Form Service Advisor tidak ditemukan.',

            'id_mekanik.exists' =>
            'Mekanik tidak ditemukan atau sedang nonaktif.',

            'catatan_mekanik.max' =>
            'Catatan mekanik maksimal 2000 karakter.',
        ];
    }
}
