<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::orderBy('created_at','asc')->get();
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
        $task->save();
        
        return to_route('tasks.index');
    }

    public function destroy(Request $request, Task $task)
    {
        $task->delete();
        return to_route('tasks.index');
    }
}