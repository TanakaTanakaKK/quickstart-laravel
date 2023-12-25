<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckPostalCode implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $postalCode = mb_convert_kana($value, "a");
        $postalCode = str_replace("-","",$postalCode);
        $postalCodeLength = strlen($postalCode);

        if($postalCodeLength != 7 ){
            $fail("郵便番号に7桁以外は登録できません。");
        }elseif(preg_match('/[^\p{N}]/u',$postalCode)){
            $fail("郵便番号に ー - 以外の記号は使用できません。");
        }
    }
}
