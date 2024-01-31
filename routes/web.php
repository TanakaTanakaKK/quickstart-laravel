<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthenticationController,
    LoginCredentialController,
    PasswordResetAuthenticationController,
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
    Route::get('/create', [AuthenticationController::class, 'create'])->name('authentications.create');
    Route::post('', [AuthenticationController::class, 'store'])->name('authentications.store');
    Route::get('/complete', [AuthenticationController::class, 'complete'])->name('authentications.complete');
});

Route::prefix('/users')->group(function () {
    Route::get('/create/{authentication_token}', [UserController::class, 'create'])->name('users.create');
    Route::post('/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/complete/{user}', [UserController::class, 'complete'])->name('users.complete');
    Route::get('/{password_reset_token}', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/{user}', [UserController::class, 'update'])->name('users.update');
});

Route::prefix('/login_credential')->group(function () {
    Route::get('/create', [LoginCredentialController::class, 'create'])->name('login_credential.create');
    Route::post('/store', [LoginCredentialController::class, 'store'])->name('login_credential.store');
    Route::get('/destroy', [LoginCredentialController::class, 'destroy'])->name('login_credential.destroy');
});

Route::prefix('/password_reset_authentication')->group(function () {
    Route::get('/create', [PasswordResetAuthenticationController::class, 'create'])->name('password_reset_authentication.create');
    Route::post('', [PasswordResetAuthenticationController::class, 'store'])->name('password_reset_authentication.store');
    Route::get('/complete', [PasswordResetAuthenticationController::class, 'complete'])->name('password_reset_authentication.complete');
});
