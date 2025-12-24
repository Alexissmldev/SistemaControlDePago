<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'student_id'           => 'required|exists:students,id',
            'exchange_rate'        => 'required|numeric|min:0.01',
            'payments'             => 'required|array|min:1',
            'payments.*.method'    => 'required|string',
            'payments.*.amount'    => 'required|numeric|min:0.01',
            'payments.*.reference' => 'nullable|string',
        ];
    }
}