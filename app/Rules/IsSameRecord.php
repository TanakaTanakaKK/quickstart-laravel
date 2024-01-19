<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\LoginCredential;

class IsSameRecord implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(LoginCredential::where('token', session('login_credential_token'))->first()->users->$attribute == $value){
            $fail('既に登録されている:attributeと同じです。');
        }
    }
}
