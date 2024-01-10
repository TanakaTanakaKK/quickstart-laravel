<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthenticationRequest extends FormRequest
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
                'unique:users,email',
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