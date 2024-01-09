<?php

namespace App\Http\Requests;

use Illuminate\{
    Foundation\Http\FormRequest,
    Validation\Rules\Enum
};
use App\Enums\Prefectures;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => [
                "required",
                "regex:/^[ぁ-んァ-ヶ一-龠]+$/u"
            ],
            "kana_name" => [
                "required",
                "regex:/^[ァ-ヶ]+$/u"
            ],
            "nickname" => "required",
            "gender" => "required",
            "birthday" => [
                "required",
                'before:today'
            ],
            "phone_number" => [
                "required",
                "regex:/^[0-9]{3}-?[0-9]{4}-?[0-9]{4}$/",
                "unique:users,phone_number"
            ],
            "postalcode" => [
                "required",
                "regex:/^[0-9]{3}-?[0-9]{4}$/"
            ],
            "prefecture" => [
                "required",
                //new Enum(Prefectures::class)
            ],
            "city" => [
                "required",
                "regex:/^[ぁ-んァ-ヶ一-龠]+$/u"
            ],
            "block" => [
                "required",
                "regex:/^[0-9]+-?[0-9]+?-?[0-9]+?$/"
            ],
            "building" => [
                'nullable',
                'regex:/^[ぁ-んァ-ヶ一-龠a-zA-Z0-9p{L}\p{N}\-ー〜 ]+$/u'
            ]
        ];
    }
    public function attributes(): array
    {
        return [
            'name' => '氏名',
            'kana_name' => '氏名(カナ)',
            'nickname' => 'ニックネーム',
            'gender' => '性別',
            'birthday' => '誕生日',
            'phone_number' => '電話番号',
            'postalcode' => '郵便番号',
            'prefecture' => '都道府県',
            'city' => '市区町村',
            'block' => '番地',
        ];
    }
}