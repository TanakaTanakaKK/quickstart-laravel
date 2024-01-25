<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use BenSampo\Enum\Rules\EnumValue;

class TaskUpdateRequest extends FormRequest
{
    public function rules(): array
        {
        return [
            'image_file' => [
                'nullable',
                'file',
                'image',
                'mimes:jpg,gif,png,webp',
                'max:2048'
            ],
            'name' => [
                'nullable',
                'max:128',
                'string',
            ],
            'detail' => [
                'nullable',
                'max:128',
                'string'
            ],
            'expired_at' => [
                'nullable',
                'after:today',
                'date'
            ],
            'status' => [
                'nullable',
                'integer',
                new EnumValue(TaskStatus::class, false)
            ],
        ];
    }

public function attributes(): array
{
    return [
        'name' => 'タスク名',
        'detail' => 'タスクの詳細',
        'status' => 'タスクのステータス',
        'expired_at' => 'タスクの期限',
        'image_file' => '画像ファイル'
    ];
}
}
