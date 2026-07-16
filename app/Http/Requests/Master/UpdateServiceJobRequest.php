<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'kode_motor' => strtoupper(trim((string) $this->kode_motor)),
            'keterangan' => trim((string) $this->keterangan),
            'harga' => preg_replace('/[^0-9]/', '', (string) $this->harga),
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function rules(): array
    {
        return [
            'kode_motor' => [
                'required',
                'string',
                'max:10',
                'regex:/^[A-Z0-9]+$/',
            ],
            'keterangan' => [
                'required',
                'string',
                'max:255',
            ],
            'harga' => [
                'required',
                'integer',
                'min:0',
                'max:999999999999',
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_motor.required' => 'Kode motor wajib diisi.',
            'kode_motor.regex' => 'Kode motor hanya boleh berisi huruf dan angka.',
            'keterangan.required' => 'Keterangan jasa wajib diisi.',
            'harga.required' => 'Harga jasa wajib diisi.',
            'harga.integer' => 'Harga jasa harus berupa angka.',
            'harga.min' => 'Harga jasa tidak boleh kurang dari nol.',
        ];
    }
}
