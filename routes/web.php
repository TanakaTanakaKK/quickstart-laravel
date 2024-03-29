<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthenticationController,
    LoginCredentialController,
    TaskController,
    UserController
};

Route::middleware(['auth.user'])->group(function () {
    Route::get('/', function () {
        return to_route('login_credential.create');
    });
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/task', [TaskController::class, 'store'])->name('tasks.store');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});

Route::prefix('/authentications')->group(function () {
    Route::get('/create/{authentication_type}', [AuthenticationController::class, 'create'])->name('authentications.create');
    Route::post('', [AuthenticationController::class, 'store'])->name('authentications.store');
    Route::get('/complete', [AuthenticationController::class, 'complete'])->name('authentications.complete');
});

Route::prefix('/users')->group(function () {
    Route::get('/create/{authentication_token}', [UserController::class, 'create'])->name('users.create');
    Route::post('/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/{authentication_token}/password', [UserController::class, 'editPassword'])->name('users.edit_password');
    Route::patch('/{user}', [UserController::class, 'updatePassword'])->name('users.update_password');
    Route::get('/complete/{user}', [UserController::class, 'complete'])->name('users.complete');
});

Route::prefix('/login_credential')->group(function () {
    Route::get('/create', [LoginCredentialController::class, 'create'])->name('login_credential.create');
    Route::post('/store', [LoginCredentialController::class, 'store'])->name('login_credential.store');
    Route::get('/destroy', [LoginCredentialController::class, 'destroy'])->name('login_credential.destroy');
});
