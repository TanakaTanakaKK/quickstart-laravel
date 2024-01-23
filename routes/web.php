<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthenticationController,
    LoginCredentialController,
    ResetPasswordController,
    ResetEmailController,
    TaskController,
    UserController,
};

Route::group(['middleware' => ['auth.user', 'weather']], function () {

    Route::get('/', function () {
        return to_route('login_credential.create');
    });
    
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/task', [TaskController::class, 'store'])->name('tasks.store');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::prefix('/users')->group(function () {
        Route::get('/{login_credential_token}', [UserController::class, 'show'])->name('users.show');
        Route::get('/{login_credential_token}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('/{login_credential_token}', [UserController::class, 'update'])->name('users.update');
    });

    Route::prefix('/reset_email')->group(function () {
        Route::get('/{reset_email_token}', [ResetEmailController::class, 'edit'])->name('reset_email.edit');
        Route::patch('/{reset_email_token}', [ResetEmailController::class, 'update'])->name('reset_email.update');
        Route::get('/complete/{reset_email_token}', [ResetEmailController::class, 'complete'])->name('reset_email.complete');
    });
});


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

Route::prefix('/login_credential')->group(function () {
    Route::get('/create', [LoginCredentialController::class, 'create'])->name('login_credential.create');
    Route::post('/store', [LoginCredentialController::class, 'store'])->name('login_credential.store');
    Route::get('/destroy', [LoginCredentialController::class, 'destroy'])->name('login_credential.destroy');
});

Route::prefix('/reset_password')->group(function () {
    Route::get('/create', [ResetPasswordController::class, 'create'])->name('reset_password.create');
    Route::post('/store', [ResetPasswordController::class, 'store'])->name('reset_password.store');
    Route::get('/edit/{reset_password_token}', [ResetPasswordController::class, 'edit'])->name('reset_password.edit');
    Route::patch('/update', [ResetPasswordController::class, 'update'])->name('reset_password.update');
    Route::get('/complete/{reset_password_token}', [ResetPasswordController::class, 'complete'])->name('reset_password.complete');   
});
