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
        if((int)$this->authentication_type === AuthenticationType::USER_REGISTER || (int)$this->authentication_type === AuthenticationType::EMAIL_RESET){
            return [
                'email' => [
                    'required',
                    'email:filter',
                    'unique:users,email',
                    'max:255',
                    'string'
                ],
                'type' => [
                    'required',
                    'integer',
                    new EnumValue(AuthenticationType::class, false)
                ]
            ];
        }elseif((int)$this->authentication_type === AuthenticationType::PASSWORD_RESET){
            return [
                'email' => [
                    'required',
                    'email:filter',
                    'exists:users,email',
                    'max:255',
                    'string'
                ],
                'type' => [
                    'required',
                    'integer',
                    new EnumValue(AuthenticationType::class, false)
                ]
            ];
        }
        
    }

    public function attributes(): array
    {
        return [
            'email' => 'メールアドレス',
        ];
    }
}