<?php

namespace App\Http\Requests\Transaction;

use App\Models\WorkOrderJob;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateWorkOrderJobRequest extends FormRequest
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
            'qty' => $qty !== '' ? $qty : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'qty' => [
                'required',
                'integer',
                'min:1',
                'max:999',
            ],
        ];
    }

    public function withValidator(
        Validator $validator
    ): void {
        $validator->after(function (
            Validator $validator
        ) {
            $routeJob = $this->route(
                'workOrderJob'
            );

            $workOrderJob =
                $routeJob instanceof WorkOrderJob
                ? $routeJob
                : WorkOrderJob::query()
                ->find($routeJob);

            if (! $workOrderJob) {
                return;
            }

            $workOrderJob->loadMissing(
                'workOrder'
            );

            if (
                ! $workOrderJob->workOrder ||
                ! $workOrderJob
                    ->workOrder
                    ->isEditable()
            ) {
                $validator->errors()->add(
                    'qty',
                    'Jasa hanya dapat diubah ketika Work Order berstatus DRAFT atau MENUNGGU.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'qty.required' =>
            'Jumlah jasa wajib diisi.',

            'qty.integer' =>
            'Jumlah jasa harus berupa bilangan bulat.',

            'qty.min' =>
            'Jumlah jasa minimal satu.',

            'qty.max' =>
            'Jumlah jasa maksimal 999.',
        ];
    }
}
