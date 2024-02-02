<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskCsvFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'csv_file' => [
                'required',
                'max:2000',
                'file',
                'mimes:csv,txt',
            ]
        ];
    }

    public function attributes(): array
    {
        return [
            'csv_file' => 'CSVファイル'
        ];
    }
}
