<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMechanicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama_mekanik' => trim(
                (string) $this->nama_mekanik
            ),
            'no_hp' => trim((string) $this->no_hp),
            'status_aktif' => $this->boolean(
                'status_aktif'
            ),
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_mekanik' => [
                'required',
                'string',
                'max:100',
            ],
            'no_hp' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9+\-\s()]+$/',
            ],
            'status_aktif' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_mekanik.required' =>
            'Nama mekanik wajib diisi.',
            'nama_mekanik.max' =>
            'Nama mekanik maksimal 100 karakter.',

            'no_hp.regex' =>
            'Format nomor HP tidak valid.',
            'no_hp.max' =>
            'Nomor HP maksimal 20 karakter.',
        ];
    }
}
