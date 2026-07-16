<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class StorePartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'part_number' => strtoupper(trim((string) $this->part_number)),
            'nama_part' => trim((string) $this->nama_part),
            'harga' => preg_replace('/[^0-9]/', '', (string) $this->harga),
            'qty_stock' => preg_replace('/[^0-9]/', '', (string) $this->qty_stock),
            'is_active' => $this->has('is_active')
                ? $this->boolean('is_active')
                : true,
        ]);
    }

    public function rules(): array
    {
        return [
            'part_number' => [
                'required',
                'string',
                'max:30',
                'regex:/^[A-Z0-9]+$/',
                'unique:master_parts,part_number',
            ],
            'nama_part' => [
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
            'qty_stock' => [
                'required',
                'integer',
                'min:0',
                'max:4294967295',
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
            'part_number.required' => 'Part Number wajib diisi.',
            'part_number.regex' => 'Part Number hanya boleh berisi huruf dan angka.',
            'part_number.unique' => 'Part Number sudah digunakan.',
            'nama_part.required' => 'Nama part wajib diisi.',
            'harga.required' => 'Harga part wajib diisi.',
            'harga.integer' => 'Harga part harus berupa angka.',
            'harga.min' => 'Harga part tidak boleh kurang dari nol.',
            'qty_stock.required' => 'Qty Stock wajib diisi.',
            'qty_stock.integer' => 'Qty Stock harus berupa bilangan bulat.',
            'qty_stock.min' => 'Qty Stock tidak boleh kurang dari nol.',
        ];
    }
}
