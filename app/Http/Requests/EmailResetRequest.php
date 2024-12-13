<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailResetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email:filter',
                'unique:users,email',
                'max:255',
                'string'
            ]
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'メールアドレス',
        ];
    }
}
