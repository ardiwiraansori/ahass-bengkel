<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id_customer' => trim(
                (string) $this->id_customer
            ),

            'no_plat' => strtoupper(
                preg_replace(
                    '/\s+/',
                    ' ',
                    trim((string) $this->no_plat)
                )
            ),

            'kode_motor' => strtoupper(
                trim((string) $this->kode_motor)
            ),

            'nama_unit' => trim(
                (string) $this->nama_unit
            ),

            'no_rangka' => strtoupper(
                preg_replace(
                    '/\s+/',
                    '',
                    trim((string) $this->no_rangka)
                )
            ),

            'no_mesin' => strtoupper(
                preg_replace(
                    '/\s+/',
                    '',
                    trim((string) $this->no_mesin)
                )
            ),
        ]);
    }

    public function rules(): array
    {
        return [
            'id_customer' => [
                'required',
                'string',
                'exists:customers,id_customer',
            ],

            'no_plat' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Z0-9\s\-]+$/',
                'unique:vehicles,no_plat',
            ],

            'kode_motor' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Z0-9\-]+$/',
            ],

            'nama_unit' => [
                'required',
                'string',
                'max:255',
            ],

            'tahun' => [
                'required',
                'integer',
                'min:1980',
                'max:' . (date('Y') + 1),
            ],

            'no_rangka' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Z0-9\-]+$/',
                'unique:vehicles,no_rangka',
            ],

            'no_mesin' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Z0-9\-]+$/',
                'unique:vehicles,no_mesin',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_customer.required' =>
            'Customer wajib dipilih.',

            'id_customer.exists' =>
            'Data customer tidak ditemukan.',

            'no_plat.required' =>
            'Nomor plat wajib diisi.',

            'no_plat.regex' =>
            'Format nomor plat tidak valid.',

            'no_plat.unique' =>
            'Nomor plat sudah digunakan kendaraan lain.',

            'kode_motor.required' =>
            'Kode motor wajib diisi.',

            'kode_motor.regex' =>
            'Kode motor hanya boleh berisi huruf, angka, dan tanda hubung.',

            'nama_unit.required' =>
            'Nama unit wajib diisi.',

            'tahun.required' =>
            'Tahun kendaraan wajib diisi.',

            'tahun.integer' =>
            'Tahun kendaraan harus berupa angka.',

            'tahun.min' =>
            'Tahun kendaraan minimal 1980.',

            'tahun.max' =>
            'Tahun kendaraan tidak valid.',

            'no_rangka.required' =>
            'Nomor rangka wajib diisi.',

            'no_rangka.regex' =>
            'Format nomor rangka tidak valid.',

            'no_rangka.unique' =>
            'Nomor rangka sudah digunakan kendaraan lain.',

            'no_mesin.required' =>
            'Nomor mesin wajib diisi.',

            'no_mesin.regex' =>
            'Format nomor mesin tidak valid.',

            'no_mesin.unique' =>
            'Nomor mesin sudah digunakan kendaraan lain.',
        ];
    }
}
