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
        $email_validation = match ((int)$this->authentication_type) {
            AuthenticationType::USER_REGISTER => 'unique:users,email',
            AuthenticationType::PASSWORD_RESET => 'exists:users,email',
            default => ''
        };
            return [
                'email' => [
                    'required',
                    'email:filter',
                    'max:255',
                    'string',
                    $email_validation
                ],
                'authentication_type' => [
                    'required',
                    'integer',
                    new EnumValue(AuthenticationType::class, false)
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