<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use BenSampo\Enum\Rules\EnumValue;
use Exception;
use Carbon\Carbon;

class TaskCsvFileRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $failed_task_count = [];
        $csv_file = explode("\n", file_get_contents($this->csv_file));
        foreach($csv_file as $index => $csv){
            $csv = explode(',', $csv);

            try{
                $csv_array[$index]['name'] = $csv[0];
                $csv_array[$index]['detail'] = $csv[1];
                $csv_array[$index]['expired_at'] = Carbon::parse($csv[2]);
                $csv_array[$index]['status'] = TaskStatus::getValue($csv[3]);
            }catch(Exception $e){
                $failed_task_count[] = $index;
            }
        }
        $this->merge(['csv_array' => $csv_array]);
    }

    public function rules(): array
    {
        return [
            'csv_file' => [
                'required',
                'max:2000',
                'file',
                'mimes:csv,txt',
            ],
            'csv_array.*.name' => [
                'bail',
                'required',
                'max:128',
                'string',
            ],
            'csv_array.*.detail' => [
                'bail',
                'required',
                'max:128',
                'string'
            ],
            'csv_array.*.expired_at' => [
                'bail',
                'required',
                'date'
            ],
            'csv_array.*.status' => [
                'bail',
                'required',
                'integer',
                new EnumValue(TaskStatus::class, false)
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'csv_file' => 'CSVファイル',
            'csv_array.*.name' => 'CSVファイル:position行目のタスク名',
            'csv_array.*.detail' => 'CSVファイル:position行目の内容',
            'csv_array.*.expired_at' => 'CSVファイル:position行目の期限',
            'csv_array.*.status' => 'CSVファイル:position行目のステータス'
        ];
    }
}
