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
        }elseif(preg_match("/^([0-9０-９]+丁目[0-9０-９]+番地|[0-9０-９]+番地|[0-9０-９]+-[0-9０-９]+|[0-9０-９]+ー[0-9０-９]+)$/",$value)){
            $fail('番地の書き方が正しくありません。');
        }
    }
}
