<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckCity implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(preg_match("/[[:space:]]/u",$value)){
            $fail('市にスペースを入力しないでください。');
        }elseif(preg_match("/[a-zA-Zａ-ｚＡ-Ｚ]/u",$value)){
            $fail('市に英語は使えません。');
        }elseif(preg_match("/[0-9０-９]/u",$value)){
            $fail('市に数字は使えません。');
        }elseif(preg_match("/\A[ｦ-ﾟ]+\z/u",$value)){
            $fail('市に半角カナは使えません。');
        }elseif(preg_match("/[^\p{L}\p{N}]+/u",$value)){
            $fail('市に記号は使えません');
        }
    }
}
