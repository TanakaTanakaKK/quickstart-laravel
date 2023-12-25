<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckBlock implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(preg_match("/[a-zA-Zａ-ｚＡ-Ｚ]/u",$value)){
            $fail('番地に英語は使用できません。');
        }elseif(preg_match("/[[:space:]]/u",$value)){
            $fail('番地にスペースを入力しないでください。');
        }
    }
}
