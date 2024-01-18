<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthenticationController,
    LoginSessionController,
    ResetPasswordController,
    TaskController,
    UserController
};

Route::middleware(['auth.user'])->group(function () {

    Route::get('/', function () {
        return to_route('tasks.index');
    });
    
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/task', [TaskController::class, 'store'])->name('tasks.store');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    
    Route::prefix('/authentications')->group(function () {
        Route::get('/create', [AuthenticationController::class, 'create'])->name('authentications.create');
        Route::post('', [AuthenticationController::class, 'store'])->name('authentications.store');
        Route::get('/complete/{authentication_token}', [AuthenticationController::class, 'complete'])->name('authentications.complete');
    });

    Route::prefix('/users')->group(function () {
        Route::get('/create/{authentication_token}', [UserController::class, 'create'])->name('users.create');
        Route::post('/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/complete/{authentication_token}', [UserController::class, 'complete'])->name('users.complete');
    });

    Route::prefix('/login_sessions')->group(function () {
        Route::get('/create', [LoginSessionController::class, 'create'])->name('login_sessions.create');
        Route::post('/store', [LoginSessionController::class, 'store'])->name('login_sessions.store');
        Route::get('/destroy', [LoginSessionController::class, 'destroy'])->name('login_sessions.destroy');
    });

    Route::prefix('/reset_password')->group(function () {
        Route::get('/create', [ResetPasswordController::class, 'create'])->name('reset_password.create');
        Route::post('/store', [ResetPasswordController::class, 'store'])->name('reset_password.store');
        Route::get('/edit/{reset_password_token}', [ResetPasswordController::class, 'edit'])->name('reset_password.edit');
        Route::patch('/update', [ResetPasswordController::class, 'update'])->name('reset_password.update');
        Route::get('/complete/{reset_password_token}', [ResetPasswordController::class, 'complete'])->name('reset_password.complete');   
    });
});