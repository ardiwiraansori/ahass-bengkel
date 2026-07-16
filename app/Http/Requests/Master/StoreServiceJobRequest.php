<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id_job' => strtoupper(trim((string) $this->id_job)),
            'kode_motor' => strtoupper(trim((string) $this->kode_motor)),
            'keterangan' => trim((string) $this->keterangan),
            'harga' => preg_replace('/[^0-9]/', '', (string) $this->harga),
            'is_active' => $this->has('is_active')
                ? $this->boolean('is_active')
                : true,
        ]);
    }

    public function rules(): array
    {
        return [
            'id_job' => [
                'required',
                'string',
                'max:30',
                'regex:/^[A-Z0-9]+$/',
                'unique:master_jobs,id_job',
            ],
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
            'id_job.required' => 'ID Job wajib diisi.',
            'id_job.regex' => 'ID Job hanya boleh berisi huruf dan angka.',
            'id_job.unique' => 'ID Job sudah digunakan.',
            'kode_motor.required' => 'Kode motor wajib diisi.',
            'kode_motor.regex' => 'Kode motor hanya boleh berisi huruf dan angka.',
            'keterangan.required' => 'Keterangan jasa wajib diisi.',
            'harga.required' => 'Harga jasa wajib diisi.',
            'harga.integer' => 'Harga jasa harus berupa angka.',
            'harga.min' => 'Harga jasa tidak boleh kurang dari nol.',
        ];
    }
}
