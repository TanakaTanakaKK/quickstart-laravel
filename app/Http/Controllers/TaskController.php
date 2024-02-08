<?php

namespace App\Http\Controllers;

use App\Models\{
    Task,
    LoginCredential
};
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::where('user_id', LoginCredential::where('token', $request->session()->get('login_credential_token'))->value('user_id'))
            ->orderBy('created_at', 'asc')
            ->get();
        return view('task.tasks', [
            'tasks' => $tasks
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $task = new Task;
        $task->name = $request->name;
        $task->user_id = LoginCredential::where('token', $request->session()->get('login_credential_token'))->value('user_id');
        $task->save();
        
        return to_route('tasks.index');
    }

    public function destroy(Request $request, Task $task)
    {
        $task->delete();
        return to_route('tasks.index');
    }
}