<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use BenSampo\Enum\Rules\EnumValue;
use App\Enums\{
    Prefecture,
    Gender
};
use App\Rules\IsSameRecord;

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
                'nullable',
                'regex:/^[ぁ-んァ-ヶ一-龠]+$/u',
                'max:30',
                'string',
                new IsSameRecord($this)
            ],
            'kana_name' => [
                'nullable',
                'regex:/^[ァ-ヶ]+$/u',
                'max:30',
                'string',
                new IsSameRecord($this)
            ],
            'email' => [
                'nullable',
                'email:filter',
                'unique:users,email',
                'max:255',
                'string',
            ],
            'nickname' => [
                'nullable',
                'max:30',
                'string',
                new IsSameRecord($this)
            ],
            'gender' => [
                'nullable',
                'integer',
                new EnumValue(Gender::class,false),
                new IsSameRecord($this)
            ],
            'birthday' => [
                'nullable',
                'before:today',
                'date',
                new IsSameRecord($this)
            ],
            'phone_number' => [
                'nullable',
                'regex:/^[0-9]{3}-?[0-9]{4}-?[0-9]{4}$/',
                'unique:users,phone_number',
                'string',
                new IsSameRecord($this)
            ],
            'postal_code' => [
                'nullable',
                'regex:/^[0-9]{3}-?[0-9]{4}$/',
                'string',
            ],
            'prefecture' => [
                'nullable',
                'integer',
                new EnumValue(Prefecture::class,false),
            ],
            'address' => [
                'nullable',
                'max:128',
                'string',
            ],
            'block' => [
                'nullable',
                'max:128',
                'string',
            ],
            'building' => [
                'nullable',
                'max:128',
                'string',
                new IsSameRecord($this)
            ],
            'image_file' => [
                'nullable',
                'file',
                'image',
                'mimes:jpg,gif,png,webp',
                'max:2048',
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
            'postal_code' => '郵便番号',
            'prefecture' => '都道府県',
            'address' => '市区町村',
            'block' => '番地',
            'image_file' => '画像ファイル'
        ];
    }
}
