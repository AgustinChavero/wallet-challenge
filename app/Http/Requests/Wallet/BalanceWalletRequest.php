<?php

namespace App\Http\Requests\Wallet;

use App\Http\Requests\BaseFormRequest;

class BalanceWalletRequest extends BaseFormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'document.required' => 'The document is required.',
            'phone.required' => 'The phone number is required.',

            'document.string' => 'The document must be a string value.',
            'phone.string' => 'The phone must be a string value.',
        ];
    }
}
