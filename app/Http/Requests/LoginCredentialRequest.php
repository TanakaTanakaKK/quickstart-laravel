<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class LoginCredentialRequest extends FormRequest
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
                'max:255',
                'string'
            ],
            'password' => [
                'required',
                'regex:/^[!-~]+$/',
                'between:8,255',
                'string',
                function ($attribute, $value, $fail) {
                    if (is_null(User::where('email', $this->email)->first()) || !Hash::check($value, User::where('email', $this->email)->value('password'))) {
                        $fail(':attributeが一致していません。');
                    }
                }
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
