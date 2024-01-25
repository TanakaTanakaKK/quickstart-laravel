<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskSearchWordRequest extends FormRequest
{
    
    public function rules(): array
    {
        return [
            'search_word' => [
                'nullable',
                'string',
                'max:128'
            ]
        ];
    }
}
