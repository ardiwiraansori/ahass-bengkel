<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class StoreMechanicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id_mekanik' => strtoupper(
                trim((string) $this->id_mekanik)
            ),
            'honda_id_mekanik' => strtoupper(
                trim((string) $this->honda_id_mekanik)
            ),
            'nama_mekanik' => trim(
                (string) $this->nama_mekanik
            ),
            'no_hp' => trim((string) $this->no_hp),
            'status_aktif' => $this->has('status_aktif')
                ? $this->boolean('status_aktif')
                : true,
        ]);
    }

    public function rules(): array
    {
        return [
            'id_mekanik' => [
                'required',
                'string',
                'max:30',
                'regex:/^[A-Z0-9\/\-]+$/',
                'unique:mechanics,id_mekanik',
            ],
            'honda_id_mekanik' => [
                'required',
                'string',
                'max:30',
                'regex:/^[A-Z0-9\-]+$/',
                'unique:mechanics,honda_id_mekanik',
            ],
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
            'id_mekanik.required' =>
            'ID mekanik wajib diisi.',
            'id_mekanik.regex' =>
            'Format ID mekanik tidak valid.',
            'id_mekanik.unique' =>
            'ID mekanik sudah digunakan.',

            'honda_id_mekanik.required' =>
            'Honda ID mekanik wajib diisi.',
            'honda_id_mekanik.regex' =>
            'Honda ID hanya boleh berisi huruf, angka, dan tanda hubung.',
            'honda_id_mekanik.unique' =>
            'Honda ID mekanik sudah digunakan.',

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
