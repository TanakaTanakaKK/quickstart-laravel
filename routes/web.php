<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    TaskController,
    UserController,
    AuthenticationController,
    LoginSessionController
};

Route::get('/', function () {
    return to_route('tasks.index');
});

Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/task', [TaskController::class, 'store'])->name('tasks.store');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

Route::get('/authentications/create', [AuthenticationController::class, 'create'])->name('authentications.create');
Route::post('/authentications', [AuthenticationController::class, 'store'])->name('authentications.store');
Route::get('/authentications/complete/{authentication_token}',[AuthenticationController::class, 'complete'])->name('authentications.complete');

Route::get('/users/create/{authentication_token}', [UserController::class, 'create'])->name('users.create');
Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
Route::get('/users/complete/{authentication_token}', [UserController::class, 'complete'])->name('users.complete');

Route::get('/login/create', [LoginSessionController::class, 'create'])->name('login.create');
Route::post('login/store',[LoginSessionController::class,'store'])->name('login.store');
Route::get('/login/destroy', [LoginSessionController::class, 'destroy'])->name('login.destroy');