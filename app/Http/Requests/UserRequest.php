<?php

namespace App\Http\Requests;

use App\Enums\{
    Prefecture,
    Gender
};
use Illuminate\Foundation\Http\FormRequest;
use BenSampo\Enum\Rules\EnumValue;

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
                'max:30',
                'string'
            ],
            'kana_name' => [
                'required',
                'regex:/^[ァ-ヶ]+$/u',
                'max:30',
                'string'
            ],
            'nickname' => [
                'required',
                'max:30',
                'string'
            ],
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
            'postal_code' => [
                'required',
                'regex:/^[0-9]{3}-?[0-9]{4}$/',
                'string'
            ],
            'prefecture' => [
                'required',
                'integer',
                new EnumValue(Prefecture::class,false)
            ],
            'address' => [
                'required',
                'max:128',
                'string'
            ],
            'block' => [
                'required',
                'max:128',
                'string'
            ],
            'building' => [
                'nullable',
                'max:128',
                'string'
            ],
            'password' => [
                'required',
                'min:8',
                'max:128',
                'regex:/^[!-~]+$/',
                'string',
                'confirmed'
            ],
            'image_file' => [
                'required',
                'file',
                'image',
                'mimes:jpg,gif,png,webp',
                'max:2048'
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
            'postal_code' => '郵便番号',
            'prefecture' => '都道府県',
            'address' => '市区町村',
            'block' => '番地',
            'password' => 'パスワード',
            'image_file' => '画像ファイル'
        ];
    }
}