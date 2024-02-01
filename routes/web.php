<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthenticationController,
    LoginCredentialController,
    TaskController,
    UserController
};

Route::group(['middleware' => ['auth.user', 'weather']], function () {
    Route::get('/', function () {
        return to_route('login_credential.create');
    });
    Route::prefix('/task')->group(function () {
        Route::get('', [TaskController::class, 'index'])->name('task.index');
        Route::get('/create', [TaskController::class, 'create'])->name('task.create');
        Route::post('/store', [TaskController::class, 'store'])->name('task.store');
        Route::get('/show/{task}', [TaskController::class, 'show'])->name('task.show');
        Route::get('/edit/{task}', [TaskController::class, 'edit'])->name('task.edit');
        Route::patch('/update/{task}', [TaskController::class, 'update'])->name('task.update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('task.destroy');
    });
    Route::prefix('/users')->group(function () {
        Route::get('/', [UserController::class, 'show'])->name('users.show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::get('/{authentication_token}/edit_email', [UserController::class, 'edit_email'])->name('users.edit_email');
        Route::patch('/{user}/email', [UserController::class, 'update_email'])->name('users.update_email');
        Route::patch('/{user}', [UserController::class, 'update'])->name('users.update');
    });
    Route::get('authentications/create/email', [AuthenticationController::class, 'create_email'])->name('authentications.create_email');
});

Route::prefix('/authentications')->group(function () {
    Route::get('/create', [AuthenticationController::class, 'create'])->name('authentications.create');
    Route::get('/create/password', [AuthenticationController::class, 'create_password'])->name('authentications.create_password');
    Route::post('', [AuthenticationController::class, 'store'])->name('authentications.store');
    Route::get('/complete', [AuthenticationController::class, 'complete'])->name('authentications.complete');
});
Route::prefix('/users')->group(function () {
    Route::get('/create/{authentication_token}', [UserController::class, 'create'])->name('users.create');
    Route::post('/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/{authentication_token}/edit_password', [UserController::class, 'edit_password'])->name('users.edit_password');
    Route::patch('/{user}/password', [UserController::class, 'update_password'])->name('users.update_password');
    Route::get('/complete/{user}', [UserController::class, 'complete'])->name('users.complete');
});
Route::prefix('/login_credential')->group(function () {
    Route::get('/create', [LoginCredentialController::class, 'create'])->name('login_credential.create');
    Route::post('/store', [LoginCredentialController::class, 'store'])->name('login_credential.store');
    Route::get('/destroy', [LoginCredentialController::class, 'destroy'])->name('login_credential.destroy');
});
