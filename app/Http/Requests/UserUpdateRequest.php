<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use BenSampo\Enum\Rules\EnumValue;
use App\Enums\{
    Prefecture,
    Gender
};

class UserUpdateRequest extends FormRequest
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
                'regex:/^[ぁ-んァ-ヶ一-龠]+$/u',
                'max:30',
                'string'
            ],
            'kana_name' => [
                'regex:/^[ァ-ヶ]+$/u',
                'max:30',
                'string'
            ],
            'nickname' => [
                'max:30',
                'string'
            ],
            'gender' => [
                'integer',
                new EnumValue(Gender::class,false)
            ],
            'birthday' => [
                'before:today',
                'date'
            ],
            'phone_number' => [
                'regex:/^[0-9]{3}-?[0-9]{4}-?[0-9]{4}$/',
                'unique:users,phone_number',
                'string'
            ],
            'postal_code' => [
                'regex:/^[0-9]{3}-?[0-9]{4}$/',
                'string'
            ],
            'prefecture' => [
                'integer',
                new EnumValue(Prefecture::class,false)
            ],
            'address' => [
                'string'
            ],
            'block' => [
                'string'
            ],
            'building' => [
                'nullable',
                'string'
            ],
            'image_file' => [
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
            'image_file' => '画像ファイル'
        ];
    }
}
