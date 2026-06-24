<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:255'],
            'email'   => [
                'required',
                'email',
                'max:255',
                Rule::unique('customers')->ignore($this->route('customer')),
            ],
            'phone'   => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
        ];
    }
}