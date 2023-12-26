<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    TaskController,
    RegisterController,
    MailAuthenticationController
};


Route::get('/', function () {
    return redirect('/tasks');
});
Route::get('/tasks', [TaskController::class, 'index']);
Route::post('/task',[TaskController::class,'store']);
Route::delete('/task/{task}',[TaskController::class,'destroy']);
Route::get('/register',[MailAuthenticationController::class,'index']);
Route::post('/register',[MailAuthenticationController::class,'sendMail']);
Route::post('/create_user',[RegisterController::class,'register']);
Route::get('/create_user/{token}',[RegisterController::class,'checkToken']);








