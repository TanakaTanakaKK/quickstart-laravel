<?php

namespace App\Http\Controllers;

use App\Models\{
    Task,
    TaskComment
};
use Illuminate\Http\Request;
use App\Http\Requests\TaskCommentRequest;
use Gate;
use Exception;

class TaskCommentController extends Controller
{
    public function store(TaskCommentRequest $request)
    {
        if(!Gate::allows('isAdmin') && (int)$request->user_id !== auth()->id()){
            return to_route('task.show', $request->task_id)->withErrors(['access_error' => 'アクセスが無効です。']);
        }
        
        try{
            TaskComment::create([
                'user_id' => auth()->id(),
                'task_id' => $request->task_id,
                'comment' => $request->comment 
            ]);
        }catch(Exception $e){
            return to_route('task.show', $request->task_id)->withErrors(['comment_error' => 'コメントの投稿に失敗しました。']);
        }

        return to_route('task.show', $request->task_id);
    }

    public function destroy(Request $request, Task $task, TaskComment $task_comment)
    {
        if(!Gate::allows('isAdmin') && (int)$request->user_id !== auth()->id()){
            return to_route('task.show', $task_comment->task_id)->withErrors(['access_error' => 'アクセスが無効です。']);
        }

        $task_comment->delete();
        return to_route('task.show', $task_comment->task_id);
    }
}
