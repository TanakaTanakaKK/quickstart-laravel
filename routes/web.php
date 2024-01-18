<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    TaskController,
    UserController,
    AuthenticationController,
    LoginSessionController,
    ResetPasswordController
};

Route::middleware(['auth.user'])->group(function () {

    Route::get('/', function () {
        return to_route('tasks.index');
    });
    
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/task', [TaskController::class, 'store'])->name('tasks.store');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    
    Route::get('/authentications/create', [AuthenticationController::class, 'create'])->name('authentications.create');
    Route::post('/authentications', [AuthenticationController::class, 'store'])->name('authentications.store');
    Route::get('/authentications/complete/{authentication_token}', [AuthenticationController::class, 'complete'])->name('authentications.complete');
    
    Route::get('/users/create/{authentication_token}', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/complete/{authentication_token}', [UserController::class, 'complete'])->name('users.complete');
    
    Route::get('/login_sessions/create', [LoginSessionController::class, 'create'])->name('login_sessions.create');
    Route::post('login_sessions/store', [LoginSessionController::class, 'store'])->name('login_sessions.store');
    Route::get('/login_sessions/destroy', [LoginSessionController::class, 'destroy'])->name('login_sessions.destroy');
    
    Route::get('/reset_password/create', [ResetPasswordController::class, 'create'])->name('reset_password.create');
    Route::post('/reset_password/store', [ResetPasswordController::class, 'store'])->name('reset_password.store');
    Route::get('/reset_password/edit/{reset_password_token}', [ResetPasswordController::class, 'edit'])->name('reset_password.edit');
    Route::patch('reset_password/update', [ResetPasswordController::class, 'update'])->name('reset_password.update');
    Route::get('/reset_password/complete/{reset_password_token}', [ResetPasswordController::class, 'complete'])->name('reset_password.complete');    
});