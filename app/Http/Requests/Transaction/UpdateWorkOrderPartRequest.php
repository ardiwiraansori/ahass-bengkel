<?php

namespace App\Http\Requests\Transaction;

use App\Models\WorkOrderPart;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateWorkOrderPartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $qty = preg_replace(
            '/[^0-9]/',
            '',
            (string) $this->qty
        );

        $this->merge([
            'qty' => $qty !== ''
                ? $qty
                : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'qty' => [
                'required',
                'integer',
                'min:1',
                'max:999999',
            ],
        ];
    }

    public function withValidator(
        Validator $validator
    ): void {
        $validator->after(function (
            Validator $validator
        ) {
            $routePart = $this->route(
                'workOrderPart'
            );

            $workOrderPart =
                $routePart instanceof WorkOrderPart
                ? $routePart
                : WorkOrderPart::query()
                ->find($routePart);

            if (! $workOrderPart) {
                return;
            }

            $workOrderPart->loadMissing(
                'workOrder'
            );

            if (
                ! $workOrderPart->workOrder ||
                ! $workOrderPart
                    ->workOrder
                    ->isEditable()
            ) {
                $validator->errors()->add(
                    'qty',
                    'Part hanya dapat diubah ketika Work Order berstatus DRAFT atau MENUNGGU.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'qty.required' =>
            'Jumlah part wajib diisi.',

            'qty.integer' =>
            'Jumlah part harus berupa bilangan bulat.',

            'qty.min' =>
            'Jumlah part minimal satu.',

            'qty.max' =>
            'Jumlah part terlalu besar.',
        ];
    }
}
