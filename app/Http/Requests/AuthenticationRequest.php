<?php

namespace App\Http\Requests;

use App\Enums\AuthenticationType;
use BenSampo\Enum\Rules\EnumValue;
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
            $rules =  [
                'email' => [
                    'required',
                    'email:filter',
                    'max:255',
                    'string'
                ],
                'authentication_type' => [
                    'required',
                    'integer',
                    new EnumValue(AuthenticationType::class, false)
                ]
            ];
            $rules['email'][] = match ((int)$this->authentication_type) {
                AuthenticationType::USER_REGISTER => 'unique:users,email',
                AuthenticationType::PASSWORD_RESET => 'exists:users,email',
                default => ''
            };

            return $rules;
    }

    public function attributes(): array
    {
        return [
            'email' => 'メールアドレス',
        ];
    }
}