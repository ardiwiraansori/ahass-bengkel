<?php

namespace App\Http\Requests\Transaction;

use App\Models\ServiceAdvisorForm;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateServiceAdvisorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $kilometer = $this->kilometer;

        $this->merge([
            'id_sa' => trim(
                (string) $this->id_sa
            ),

            'id_customer' => trim(
                (string) $this->id_customer
            ),

            'vehicle_id' => $this->vehicle_id === null
                || $this->vehicle_id === ''
                ? null
                : (int) $this->vehicle_id,

            'tanggal_kedatangan' => trim(
                (string) $this->tanggal_kedatangan
            ),

            'kilometer' => $kilometer === null
                || $kilometer === ''
                ? null
                : preg_replace(
                    '/[^0-9]/',
                    '',
                    (string) $kilometer
                ),

            'keluhan' => trim(
                (string) $this->keluhan
            ),

            'catatan_sa' => trim(
                (string) $this->catatan_sa
            ) ?: null,
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

            'id_customer' => [
                'required',
                'string',
                'exists:customers,id_customer',
            ],

            'vehicle_id' => [
                'required',
                'integer',

                Rule::exists('vehicles', 'id')
                    ->where(function ($query) {
                        $query->where(
                            'id_customer',
                            $this->input('id_customer')
                        );
                    }),
            ],

            'tanggal_kedatangan' => [
                'required',
                'date',
            ],

            'kilometer' => [
                'nullable',
                'integer',
                'min:0',
                'max:999999999',
            ],

            'keluhan' => [
                'required',
                'string',
                'max:2000',
            ],

            'catatan_sa' => [
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
                ->find($this->input('id_sa'));

            if (
                $serviceAdvisorForm &&
                ! $serviceAdvisorForm->isOpen()
            ) {
                $validator->errors()->add(
                    'id_sa',
                    'Form Service Advisor hanya dapat diedit ketika berstatus OPEN.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'id_sa.required' =>
            'ID Form Service Advisor wajib dikirim.',

            'id_sa.exists' =>
            'Form Service Advisor tidak ditemukan.',

            'id_customer.required' =>
            'Customer wajib dipilih.',

            'id_customer.exists' =>
            'Data customer tidak ditemukan.',

            'vehicle_id.required' =>
            'Kendaraan wajib dipilih.',

            'vehicle_id.integer' =>
            'Kendaraan yang dipilih tidak valid.',

            'vehicle_id.exists' =>
            'Kendaraan tidak ditemukan atau bukan milik customer yang dipilih.',

            'tanggal_kedatangan.required' =>
            'Tanggal kedatangan wajib diisi.',

            'tanggal_kedatangan.date' =>
            'Format tanggal kedatangan tidak valid.',

            'kilometer.integer' =>
            'Kilometer harus berupa angka.',

            'kilometer.min' =>
            'Kilometer tidak boleh kurang dari nol.',

            'keluhan.required' =>
            'Keluhan customer wajib diisi.',

            'keluhan.max' =>
            'Keluhan maksimal 2000 karakter.',

            'catatan_sa.max' =>
            'Catatan Service Advisor maksimal 2000 karakter.',
        ];
    }
}
