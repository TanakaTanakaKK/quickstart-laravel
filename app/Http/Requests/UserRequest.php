<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use BenSampo\Enum\Rules\EnumValue;
use App\Enums\{
    Prefectures,
    Gender
};

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'regex:/^[ぁ-んァ-ヶ一-龠]+$/u',
                'string'
            ],
            'kana_name' => [
                'required',
                'regex:/^[ァ-ヶ]+$/u',
                'string'
            ],
            'nickname' => 'required',
            'gender' => [
                'required',
                'integer',
                new EnumValue(Gender::class,false)
            ],
            'birthday' => [
                'required',
                'before:today',
                'date'
            ],
            'phone_number' => [
                'required',
                'regex:/^[0-9]{3}-?[0-9]{4}-?[0-9]{4}$/',
                'unique:users,phone_number',
                'string'
            ],
            'postalcode' => [
                'required',
                'regex:/^[0-9]{3}-?[0-9]{4}$/',
                'string'
            ],
            'prefecture' => [
                'required',
                'integer',
                new EnumValue(Prefectures::class,false)
            ],
            'cities' => [
                'required',
                'regex:/^[ぁ-んァ-ヶ一-龠]+$/u',
                'string'
            ],
            'block' => [
                'required',
                'regex:/^[0-9]+-?[0-9]+?-?[0-9]+?$/',
                'string'
            ],
            'building' => [
                'nullable',
                'regex:/^[ぁ-んァ-ヶ一-龠a-zA-Z0-9p{L}\p{N}\-ー〜 ]+$/u',
                'string'
            ],
            'password' => [
                'required',
                'min:8',
                'regex:/^[!-~]+$/',
                'string',
                'confirmed'
            ],
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
            'cities' => '市区町村',
            'block' => '番地',
            'password' => 'パスワード',
        ];
    }
}