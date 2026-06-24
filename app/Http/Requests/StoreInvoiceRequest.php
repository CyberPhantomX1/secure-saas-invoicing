<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => [
                'required',
                'uuid',
                Rule::exists('customers', 'id')->where(function ($query) {
                    $query->where('user_id', auth()->id());
                })
            ],
            'invoice_date' => ['required', 'date'],
            'due_date'     => ['required', 'date', 'after_or_equal:invoice_date'],
            'discount'     => ['nullable', 'numeric', 'min:0'],
            'items'                => ['required', 'array', 'min:1'],
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.quantity'     => ['required', 'numeric', 'min:1'],
            'items.*.price'        => ['required', 'numeric', 'min:0.01'],
            'items.*.tax_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.min' => 'An invoice must have at least one item.',
            'customer_id.exists' => 'The selected customer is invalid or does not belong to your account.',
        ];
    }
}