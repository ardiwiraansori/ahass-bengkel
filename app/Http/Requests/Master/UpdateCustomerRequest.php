<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $email = strtolower(trim((string) $this->email));

        $noIdentitas = strtoupper(
            preg_replace(
                '/\s+/',
                '',
                trim((string) $this->no_identitas)
            )
        );

        $alamat = trim((string) $this->alamat);

        $this->merge([
            'id_customer' => trim(
                (string) $this->id_customer
            ),
            'nama_customer' => trim(
                (string) $this->nama_customer
            ),
            'no_hp' => trim((string) $this->no_hp),
            'email' => $email !== '' ? $email : null,
            'no_identitas' => $noIdentitas !== ''
                ? $noIdentitas
                : null,
            'alamat' => $alamat !== '' ? $alamat : null,
        ]);
    }

    public function rules(): array
    {
        $customerId = $this->input('id_customer');

        return [
            'id_customer' => [
                'required',
                'string',
                'exists:customers,id_customer',
            ],
            'nama_customer' => [
                'required',
                'string',
                'max:255',
            ],
            'no_hp' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9+\-\s()]+$/',
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('customers', 'email')
                    ->ignore($customerId, 'id_customer'),
            ],
            'no_identitas' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[A-Z0-9\-\.\/]+$/',
                Rule::unique('customers', 'no_identitas')
                    ->ignore($customerId, 'id_customer'),
            ],
            'alamat' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_customer.required' =>
            'ID customer tidak ditemukan.',
            'id_customer.exists' =>
            'Data customer tidak ditemukan.',

            'nama_customer.required' =>
            'Nama customer wajib diisi.',
            'nama_customer.max' =>
            'Nama customer maksimal 255 karakter.',

            'no_hp.required' =>
            'Nomor HP wajib diisi.',
            'no_hp.regex' =>
            'Format nomor HP tidak valid.',
            'no_hp.max' =>
            'Nomor HP maksimal 20 karakter.',

            'email.email' =>
            'Format alamat email tidak valid.',
            'email.unique' =>
            'Alamat email sudah digunakan customer lain.',

            'no_identitas.regex' =>
            'Format nomor identitas tidak valid.',
            'no_identitas.unique' =>
            'Nomor identitas sudah digunakan customer lain.',

            'alamat.max' =>
            'Alamat maksimal 1000 karakter.',
        ];
    }
}
