<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Models\{
    Task,
};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\{
    TaskCsvFileRequest,
    TaskRequest,
    TaskUpdateRequest,
    TaskSearchWordRequest
};
use Exception;
use Imagick;
use Gate;

class TaskController extends Controller
{
    public function index(TaskSearchWordRequest $request)
    {
        $tasks = Task::when(!is_null($request->query('search_word')), function($query) use($request) {
            return $query->where('name', 'LIKE', '%'.$request->query('search_word').'%')
                ->orWhere('detail', 'LIKE', '%'.$request->query('search_word').'%');

        })->when(!Gate::allows('isAdmin'), function($query) {
            return $query->where('user_id', auth()->id());
            
        })->when(!is_null($request->query('validity_time')) || $request->query('validity_time') === 'expired', function($query) use ($request){
            $request->session()->put('is_change_for_expired', true);
            return $query->where('expired_at', '<', now());
        })->when(!is_null($request->query('active')) || $request->query('validity_time') === 'active', function($query) use ($request){
            $request->session()->put('is_change_for_expired', false);
            return $query->where('expired_at', '>', now());
        })
        ->sortable()
        ->paginate(10);

        if(!is_null($request->session()->get('task_message'))){
            return view('task.index', [
                'tasks' => $tasks,
                'is_succeeded' => true,
                'task_message' => $request->session()->get('task_message')
            ]);
        }

        return view('task.index', [
            'tasks' => $tasks
        ]);
    }

    public function create(Request $request)
    {
        return view('task.create');
    }

    public function store(TaskRequest $request)
    {
        if(!is_null($request->session()->get('task_message'))){
            return to_route('task.index');
        }

        $image = new Imagick();
        $image->readImage($request->file('image_file'));
        $archive_extension = config('mimetypes')[$image->getImageMimetype()];

        $is_duplicated_archive_image_path = true;
        while($is_duplicated_archive_image_path){
            $archive_image_path = Str::random(rand(20, 50)).$archive_extension;
            $is_duplicated_archive_image_path = Task::where('archive_image_path', $archive_image_path)->exists();
        }

        if(!is_null($image->getImageProperties("exif:*"))){
            $image->stripImage();
        }

        Storage::put('public/task/archive_images/'.$archive_image_path, $image);

        $is_duplicated_thumbnail_image_path = true;
        while($is_duplicated_thumbnail_image_path){
            $thumbnail_image_path = Str::random(rand(20, 50)).'.webp';
            $is_duplicated_thumbnail_image_path = Task::where('thumbnail_image_path', $thumbnail_image_path)->exists();
        }

        if($archive_extension !== '.webp'){
            $image->setImageFormat('webp');
        } 
        $image->resizeImage(200, 200, Imagick::FILTER_LANCZOS, 1);

        Storage::put('public/task/thumbnail_images/'.$thumbnail_image_path, $image);

        $image->clear();

        try{
            Task::create([
                'name' => $request->name,
                'user_id' => auth()->id(),
                'expired_at' => $request->expired_at,
                'detail' => $request->detail,
                'thumbnail_image_path' => $thumbnail_image_path,
                'archive_image_path' => $archive_image_path,
                'status' => $request->status
            ]);
        }catch(Exception $e){
            return to_route('task.index')->withErrors(['task_error' => 'タスクの登録に失敗しました。']);
        }

        $request->session()->flash('task_message', $request->name.'をTask Listに登録しました。');

        return to_route('task.index');
    }

    public function storeCsv(TaskCsvFileRequest $request)
    {
        foreach($request->csv_items as $data){
            try{
                Task::create([
                    'user_id' => auth()->id(),
                    'name' => $data['name'],
                    'detail' => $data['detail'],
                    'expired_at' => $data['expired_at'],
                    'status' => $data['status']
                ]);
            }catch(Exception $e){
                return to_route('task.create');
            }
        }

        $request->session()->flash('task_message', $request->csv_file->getClientOriginalName().'からタスクを登録しました。');

        return to_route('task.index');
    }

    public function show(Request $request, Task $task)
    {
        if(!Gate::allows('isAdmin') && !auth()->user()->can('view', $task)){
            return to_route('task.index')->withErrors(['access_error' => 'アクセスが無効です。']);
        }

        return view('task.show', [
            'task' => $task->load('taskComments.user'),
        ]);
    }

    public function edit(Request $request, Task $task)
    {
        if(!Gate::allows('isAdmin') && !auth()->user()->can('update', $task)){
            return to_route('task.index')->withErrors(['access_error' => '不正なアクセスです。']);
        }

        return view('task.edit', [
            'task' => $task
        ]);
    }

    public function update(TaskUpdateRequest $request, Task $task)
    {
        if(!Gate::allows('isAdmin') && !auth()->user()->can('update', $task)){
            return to_route('task.index')->withErrors(['access_error' => '不正なアクセスです。']);
        }

        if(!is_null($request->image_file)){
            $image = new Imagick();
            $image->readImage($request->file('image_file'));
            $archive_extension = config('mimetypes')[$image->getImageMimetype()];
    
            $is_duplicated_archive_image_path = true;
            while($is_duplicated_archive_image_path){
                $archive_image_path = Str::random(rand(20, 50)).$archive_extension;
                $is_duplicated_archive_image_path = Task::where('archive_image_path', $archive_image_path)->exists();
            }
    
            if(!is_null($image->getImageProperties("exif:*"))){
                $image->stripImage();
            }
    
            Storage::put('public/task/archive_images/'.$archive_image_path, $image);
            Storage::delete('public/task/archive_images/'.$task->archive_image_path);

            $is_duplicated_thumbnail_image_path = true;
            while($is_duplicated_thumbnail_image_path){
                $thumbnail_image_path = Str::random(rand(20, 50)).'.webp';
                $is_duplicated_thumbnail_image_path = Task::where('thumbnail_image_path', $thumbnail_image_path)->exists();
            }
    
            if($archive_extension !== '.webp'){
                $image->setImageFormat('webp');
            } 
            $image->resizeImage(200, 200, Imagick::FILTER_LANCZOS, 1);
    
            Storage::put('public/task/thumbnail_images/'.$thumbnail_image_path, $image);
            Storage::delete('public/task/thumbnail_images/'.$task->thumbnail_image_path);

            $image->clear();

            $task->archive_image_path = $archive_image_path;
            $task->thumbnail_image_path = $thumbnail_image_path;
        }

        try{
            $task->name = $request->name;
            $task->detail = $request->detail;
            $task->expired_at = $request->expired_at;
            $task->status = $request->status;
            $task->save();
        }catch(Exception $e){
            return to_route('task.show', $task->id)->withErrors(['task_error' => 'タスクの更新に失敗しました。']);
        }
        
        return to_route('task.show', $task);
    }

    public function exportCsv(Request $request)
    {
        $tasks = Task::when(!Gate::allows('isAdmin'), function($query) {
            return $query->where('user_id', auth()->id());
            
        })->get();

        $response_headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=task.csv',
            'Pragma' => 'no-cache',
            'Expires' => 0,
        ];

        $callback = function() use ($tasks) {
            
            $resource = fopen('php://output', 'w');
            
            if(Gate::allows('isAdmin')){
                $tasks->load('user');

                fputcsv($resource, ['ユーザー名', 'タスク名', '内容', '期限', 'ステータス']);

                foreach($tasks as $task) {
                    $task->toArray();
                    fputcsv($resource, [
                        $task->user->name,
                        $task->name, 
                        $task->detail, 
                        $task->expired_at,
                        TaskStatus::getDescription($task->status)
                    ]);
                }
            }else{
                fputcsv($resource, ['タスク名', '内容', '期限', 'ステータス']);

                foreach($tasks as $task) {
                    $task->toArray();
                    fputcsv($resource, [
                        $task->name, 
                        $task->detail, 
                        $task->expired_at,
                        TaskStatus::getDescription($task->status)
                    ]);
                }
            }
            fclose($resource);
        };

        return response()->stream($callback, 200, $response_headers);
    }

    public function destroy(Request $request, Task $task)
    {
        if(!Gate::allows('isAdmin') && !auth()->user()->can('delete', $task)){
            return to_route('task.index')->withErrors(['access_error' => 'アクセスが無効です。']);
        }

        $task->delete();
        return to_route('task.index');
    }
}