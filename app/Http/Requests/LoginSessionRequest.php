<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\HasUsersRecord;

class LoginSessionRequest extends FormRequest
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
                'exists:users,email',
                'string'
            ],
            'password' => [
                'required',
                'regex:/^[!-~]+$/',
                'string',
                new HasUsersRecord($this)
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'メールアドレス',
            'password' => 'パスワード',
        ];
    }
}