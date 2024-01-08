<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    TaskController,
    RegisterController,
    TokenController
};
Route::get('/', function () {
    return to_route('home');
});
Route::get('/create_user',function(){
    return to_route('home')
    ->withErrors(['route_error' => '仮登録完了メールから会員登録画面に進んでください。']);
});
Route::get('/tasks', [TaskController::class, 'index'])->name('home');
Route::post('/task',[TaskController::class,'store'])->name('task.store');
Route::delete('/task/{task}',[TaskController::class,'destroy'])->name('task.destroy');
Route::get('/register',[TokenController::class,'index'])->name('register');
Route::post('/register',[TokenController::class,'sendMail'])->name('token.sendMail');
Route::get('/create_user/{token}',[TokenController::class,'hasToken'])->name('token.hasToken');
Route::get('/register/successful',[TokenController::class,'tokenSuccessful'])->name('token.successful');
Route::get('/create_user/successful',[RegisterController::class,'registerSuccessful'])->name('register.successful');
Route::post('/create_user',[RegisterController::class,'register'])->name('create.user');









