<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\BaseFormRequest;

class RegisterClientRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'document' => 'required|string',
            'names'    => 'required|string',
            'email'    => 'required|email',
            'phone'    => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'document.required' => 'Document is required.',
            'email.required' => 'Email is required.',
            'names.required' => 'Name is required.',
            'phone.required' => 'Phone is required.',

            'email.email' => 'The email format is invalid.',
            'names.string' => 'The names must be a string value.',
            'phone.string' => 'The phone must be a string value.',
            'document.string' => 'The document must be a string value.',
        ];
    }
}
