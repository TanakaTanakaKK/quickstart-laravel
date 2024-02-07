<?php

namespace App\Providers;

use App\Enums\UserStatus;
use App\Models\Task;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\TaskPolicy;

class AuthServiceProvider extends ServiceProvider
{
    
    protected $policies = [
        Task::class => TaskPolicy::class
    ];

    public function boot(): void
    {
        Gate::define('isAdmin', function($user) {
            return $user->status === UserStatus::ADMIN;
        });
    }
}
