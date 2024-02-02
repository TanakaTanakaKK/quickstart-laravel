<?php

namespace App\Http\Controllers;

use App\Enums\{
    CsvTaskColumn,
    TaskStatus
};
use App\Models\{
    Task,
    TaskComment,
    LoginCredential,
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

        if(is_null($request->query('search_word'))){
            if(Gate::allows('isAdmin')){
                $tasks = Task::orderBy('expired_at', 'asc')->get();
            }else{
                $tasks = Task::where('user_id', auth()->id()) 
                    ->orderBy('expired_at', 'asc')
                    ->get();
            }
        }else{
            if(Gate::allows('isAdmin')){
                $tasks = Task::where('name', 'LIKE', '%'.$request->query('search_word').'%')
                    ->orWhere('detail', 'LIKE', '%'.$request->query('search_word').'%')
                    ->orderBy('expired_at', 'asc')
                    ->get();
            }else{
                $tasks = Task::where('user_id', auth()->id()) 
                    ->where('name', 'LIKE', '%'.$request->query('search_word').'%')
                    ->orWhere('detail', 'LIKE', '%'.$request->query('search_word').'%')
                    ->orderBy('expired_at', 'asc')
                    ->get();
            }
        }

        if(!is_null($request->session()->get('task_message'))){
            $task_message = $request->session()->get('task_message');
            $request->session()->forget('task_message');
            return view('task.index', [
                'tasks' => $tasks,
                'is_succeeded' => true,
                'task_message' => $task_message
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
                'user_id' => LoginCredential::where('token', $request->session()->get('login_credential_token'))->value('user_id'),
                'expired_at' => $request->expired_at,
                'detail' => $request->detail,
                'thumbnail_image_path' => $thumbnail_image_path,
                'archive_image_path' => $archive_image_path,
                'status' => $request->status
            ]);
        }catch(Exception $e){
            return to_route('task.index')->withErrors(['task_error' => 'タスクの登録に失敗しました。']);
        }
        return to_route('task.index')->with(['task_message' => $request->name.'をTask Listに登録しました。']);
    }

    public function store_csv(TaskCsvFileRequest $request)
    {
        $csv_file = explode("\n", file_get_contents($request->csv_file));
        $succeeded_task_count = 0;
        $failed_task_count = 0;
        $failed_task_rows = [];
        $file_name = $request->csv_file->getClientOriginalName();
        
        foreach($csv_file as $key => $data){
            $data = explode(',', $data);

            try{
                Task::create([
                    'user_id' => auth()->id(),
                    'name' => $data[CsvTaskColumn::NAME],
                    'detail' => $data[CsvTaskColumn::DETAIL],
                    'expired_at' => $data[CsvTaskColumn::EXPIRED_AT],
                    'status' => TaskStatus::getValue(CsvTaskColumn::STATUS)
                ]);
                $succeeded_task_count ++;

            }catch(Exception $e){
                $failed_task_count ++;
                $failed_task_rows[] = ($key+1).'行目';
            }
        }

        if($succeeded_task_count >= 1){
            $request->session()->flash('task_message', $file_name.'から'.$succeeded_task_count.'件のタスクを登録しました。');
        }

        if($failed_task_count >= 1){
            return to_route('task.index')->withErrors([
                'csv_store_error' => $file_name.'内の'.$failed_task_count.'件のタスク('.implode(',', $failed_task_rows).')が登録に失敗しました。',
            ]);
        }

        return to_route('task.index');
    }

    public function show(Request $request, Task $task)
    {
        if(!Gate::allows('isAdmin') && !auth()->user()->can('view', $task)){
            return to_route('task.index')->withErrors(['access_error' => 'アクセスが無効です。']);
        }

        $task_comments = TaskComment::where('task_id', $task->id)
            ->orderBy('created_at', 'asc')
            ->get();

        if(is_null($task_comments)){
            return view('task.show', [
                'task' => $task
            ]);
        }else{
            return view('task.show', [
                'task' => $task,
                'comments' => $task_comments
            ]);
        }
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
        
        return to_route('task.show', $task->id);
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