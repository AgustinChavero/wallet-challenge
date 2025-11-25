<?php

namespace App\Http\Requests\Payment;

use App\Http\Requests\BaseFormRequest;

class ConfirmPaymentRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'session_id' => 'required|string',
            'token'      => 'required|string|size:6',
        ];
    }

    public function messages()
    {
        return [
            'session_id.required' => 'The session_id is required.',
            'token.required' => 'The token is required.',

            'session_id.string' => 'The session_id must be a string.',
            'token.string' => 'The token must be a string.',
            'token.size' => 'The token must be exactly 6 characters.',
        ];
    }
}
