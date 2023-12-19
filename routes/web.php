<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
#P.54
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
    return view('welcome');
});
// タスク一覧
Route::get('/tasks', [TaskController::class, 'index']);
// タスクの保存
Route::post('/task',[TaskController::class,'store']);
// タスクの削除     Controllerのclass,Controllerの中にあるメソッドを指定する
Route::delete('/task/{task}',[TaskController::class,'destroy']);
// 入力フォーム
Route::get('/register-form',[RegisterController::class,'register'])












// #タスク一覧表示
// Route::get('/',function(){
//     // Task:: orderByでcreated_atをソート、getで取得
//     $tasks = Task::orderBy('created_at','asc')->get();

//     return view('tasks', [
//         'tasks' => $tasks
//     ]);
// });
// #新タスク追加
// Route::post('/task',function(Request $request){
//     #引数でPOSTデータを取得（use Illuminate\Http\Request;を記述し忘れるとエラー）

//     // 第1引数:バリデーションを行うデータ
//     // 第2引数:バリデーションルール
//     $validator = Validator::make($request->all(),[
//         'name' => 'required|max:255',
//     ]);

//     if($validator->fails()){
//         return redirect('/')
//         ->withInput()   //エラーをセッションに保存 全Viewから$errorsで見れるようになる
//         ->withErrors($validator);   //すべての内容をセッションに保存
//     }

//     $task = new Task;
//     // inputタグのname属性からタスク名を受け取る
//     $task->name = $request->name;
//     $task->save();

//     return redirect('/');
// });
// #POSTのリクエストをDELETEに見せかける記述
// // tasksテーブルのIDが６であれば、変数$taskに６が入る
// Route::delete('/task/{task}',function(Task $task){
//     // /{task}でタスク名が入る。testって指定ならtestっていうタスクが入る
//     $task->delete();
    
//     // return redirect('/');
// });

