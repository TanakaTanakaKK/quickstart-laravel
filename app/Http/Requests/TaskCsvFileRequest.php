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
        $failed_task_count = 0;
        $csv_file = mb_convert_encoding(explode("\n", file_get_contents($this->csv_file)), "UTF-8", "auto");
        $csv_file = preg_replace('/^\xEF\xBB\xBF/', '', $csv_file);
        foreach($csv_file as $index => $csv){
            $csv_row = explode(',', $csv);

            try{
                $csv_items[$index]['name'] = $csv_row[0];
                $csv_items[$index]['detail'] = $csv_row[1];
                $csv_items[$index]['expired_at'] = Carbon::parse($csv_row[2]);
                $csv_items[$index]['status'] = TaskStatus::getValue($csv_row[3]);
            }catch(Exception $e){
                $failed_task_count ++;
            }
        }
        if($failed_task_count >= 0){
            $this->merge(['failed_task_count' => $failed_task_count]);
        }
        $this->merge(['csv_items' => $csv_items]);
    }

    public function rules(): array
    {
        return [
            'csv_file' => [
                function ($attribute, $value, $fail) {
                    if (!is_null($this->failed_task_count)) {
                        $fail(':attribute内の'.$this->failed_task_count.'ヶ所の記述が正しくありません。');
                    }
                },
                'required',
                'max:2000',
                'file',
                'mimes:csv,txt',
            ],
            'csv_items.*.name' => [
                'required',
                'max:128',
                'string',
            ],
            'csv_items.*.detail' => [
                'required',
                'max:128',
                'string'
            ],
            'csv_items.*.expired_at' => [
                'required',
                'date'
            ],
            'csv_items.*.status' => [
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
            'csv_items.*.name' => 'CSVファイル:position行目のタスク名',
            'csv_items.*.detail' => 'CSVファイル:position行目の内容',
            'csv_items.*.expired_at' => 'CSVファイル:position行目の期限',
            'csv_items.*.status' => 'CSVファイル:position行目のステータス'
        ];
    }
}
