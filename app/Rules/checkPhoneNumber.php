<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckPhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phoneNumber = mb_convert_kana($value, "a");
        $phoneNumber = str_replace("-","",$phoneNumber);
        $phoneNumberLength = strlen($phoneNumber);
        if($phoneNumberLength ==12 || $phoneNumberLength == 13){
            $fail("電話番号が無効な桁数です。");
        }elseif(preg_match('/[^\p{N}]/u',$phoneNumber)){
            $fail("電話番号に ー - 以外の記号は使用できません。");
        }

    }
}
