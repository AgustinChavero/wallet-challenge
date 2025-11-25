<?php

namespace App\Http\Requests\Wallet;

use App\Http\Requests\BaseFormRequest;

class RechargeWalletRequest extends BaseFormRequest
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
            'document.required' => 'The document is required.',
            'phone.required' => 'The phone number is required.',
            'amount.required' => 'The amount is required.',

            'amount.numeric' => 'The amount must be a numeric value.',
            'amount.min' => 'The amount must be at least 0.01.',
            'phone.string' => 'The phone must be a string value.',
            'document.string' => 'The document must be a string value.',
        ];
    }
}
