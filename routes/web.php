<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\RegisterController;
//hascコード作成
use Illuminate\Support\Facades\Hash;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/tasks');
});
// タスク一覧
Route::get('/tasks', [TaskController::class, 'index']);
// タスクの保存
Route::post('/task',[TaskController::class,'store']);
// タスクの削除     Controllerのclass,Controllerの中にあるメソッドを指定する
Route::delete('/task/{task}',[TaskController::class,'destroy']);
// メールアドレス入力フォーム
Route::get('/register',[RegisterController::class,'index']);
// メールアドレス登録
Route::post('/register',[RegisterController::class,'sendMail']);
// 会員登録画面
Route::get('/create_user/{token}',[RegisterController::class,'checkToken']);








