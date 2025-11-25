<?php

namespace App\Http\Requests\Payment;

use App\Http\Requests\BaseFormRequest;

class InitiatePaymentRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'document' => 'required|string',
            'phone'    => 'required|string',
            'amount'   => 'required|numeric|min:0.01',
        ];
    }

    public function messages()
    {
        return [
            'document.required' => 'Document is required.',
            'phone.required' => 'Phone number is required.',
            'amount.required' => 'Amount is required.',

            'amount.numeric' => 'Amount must be numeric.',
            'amount.min' => 'The minimum amount allowed is 0.01.',
            'document.string' => 'The document must be a string value.',
            'phone.string' => 'The phone must be a string value.',
        ];
    }
}
