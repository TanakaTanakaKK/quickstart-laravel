<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetNewPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^[!-~]+$/',
                'confirmed'
            ]
        ];
    }

    public function attributes(): array
    {
        return [
            'password' => 'パスワード'
        ];
    }
}