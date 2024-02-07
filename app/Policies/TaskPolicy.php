<?php

namespace App\Policies;

use App\Models\{
    Task,
    User
};
use Illuminate\Auth\Access\Response;

class TaskPolicy
{

    public function viewAny(User $user)
    {
        return $user->id === auth()->id();
    }

    public function view(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }


    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }


    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }
}
