<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use App\Models\{
    Task,
    LoginCredential,
};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\{
    TaskRequest,
    TaskUpdateRequest,
    TaskSearchWordRequest
};
use Exception;
use Imagick;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        if($request->user_status === UserStatus::ADMIN){
            $tasks = Task::orderBy('expired_at', 'asc')->get();
        }else{
            $tasks = Task::where('user_id', LoginCredential::where('token', $request->session()->get('login_credential_token'))->value('user_id')) 
                ->orderBy('expired_at', 'asc')
                ->get();
        }

        if(!is_null($request->session()->get('created_task_name'))){
            $created_task_name = $request->session()->get('created_task_name');
            $request->session()->forget('created_task_name');
            return view('task.index', [
                'tasks' => $tasks,
                'is_succeeded' => true,
                'created_task_name' => $created_task_name
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
        if(!is_null($request->session()->get('created_task_name'))){
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
        return to_route('task.index')->with(['created_task_name' => $request->name]);
    }

    public function show(Request $request, Task $task)
    {
        if($request->user_status !== UserStatus::ADMIN  &&
        $task->user_id !== LoginCredential::where('token', $request->session()->get('login_credential_token'))->value('user_id')){
            return to_route('task.index')->withErrors(['access_error' => '不正なアクセスです。']);
        }

        if(is_null($request->session()->get('is_succeeded'))){
            return view('task.show', [
                'task' => $task
            ]);
        }

        $complete_messages = ['task' => $task, 'is_succeeded' => true];
        $request->session()->forget('is_succeeded');
        $complete_messages += array('task_updated_info_array' => $request->session()->get('task_updated_info_array'));
        $request->session()->forget('task_updated_info_array');
        return view('task.show')->with($complete_messages);
    }

    public function edit(Request $request, Task $task)
    {
        if($request->user_status !== UserStatus::ADMIN  &&
        $task->user_id !== LoginCredential::where('token', $request->session()->get('login_credential_token'))->value('user_id')){
            return to_route('task.index')->withErrors(['access_error' => '不正なアクセスです。']);
        }

        return view('task.edit', [
            'task' => $task
        ]);
    }

    public function update(TaskUpdateRequest $request, Task $task)
    {
        $task_updated_info_array = [];
        foreach($request->all() as $data_name => $data){
        
            if(!is_null($data) && !in_array($data_name, ['_token', '_method', 'user_status'])){

                if($data === $task->$data_name || 
                $data_name === 'expired_at' && Carbon::parse($task->expired_at)->format('Y-m-d\TH:i') === $request->expired_at){
                    continue;
                }

                if($data_name !== 'image_file'){
                    $task->$data_name = $data;
                    $task_updated_info_array[] = $data_name;
                }else{
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
                    $task->archive_image_path = $archive_image_path;

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
                    $task->thumbnail_image_path = $thumbnail_image_path;

                    $image->clear();

                    $task_updated_info_array[] = 'image_file';
                }
            }
        }
        if(count($task_updated_info_array) >= 1){
            $task->save();
        }else{
            return to_route('task.show', $task->id);
        }

        return to_route('task.show', $task->id)
            ->with([
                'is_succeeded' => true,
                'task_updated_info_array' => $task_updated_info_array
            ]);
    }

    public function search(TaskSearchWordRequest $request)
    {
        if($request->user_status === UserStatus::ADMIN){
            $tasks = Task::where('name', 'LIKE', '%'.$request->search_word.'%')
            ->orWhere('detail', 'LIKE', '%'.$request->search_word.'%')
            ->orderBy('updated_at', 'asc')
            ->get();
        }else{
            $tasks = Task::where('user_id', LoginCredential::where('token', $request->session()->get('login_credential_token'))->value('user_id'))
            ->where('name', 'LIKE', '%'.$request->search_word.'%')
            ->orWhere('detail', 'LIKE', '%'.$request->search_word.'%')
            ->orderBy('updated_at', 'asc')
            ->get();
        }

        return view('task.index',[
            'tasks' => $tasks
        ]);
    }

    public function destroy(Request $request, Task $task)
    {
        if($request->user_status !== UserStatus::ADMIN  && $task->user_id !== LoginCredential::where('token', $request->session()->get('login_credential_token'))->value('user_id')){
            return to_route('task.index')->withErrors(['access_error' => 'アクセスが無効です。']);
        }

        $task->delete();
        return to_route('task.index');
    }
}