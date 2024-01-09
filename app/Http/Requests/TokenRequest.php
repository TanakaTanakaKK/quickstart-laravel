<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TokenRequest extends FormRequest
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
                'email:filter',
                'unique:users,email'
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