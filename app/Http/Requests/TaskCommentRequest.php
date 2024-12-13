<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskCommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'comment' => [
                'required',
                'max:1000',
                'string'
            ]
        ];
    }

    public function attributes(): array
    {
        return [
            'comment' => 'コメント',
        ];
    }
}
