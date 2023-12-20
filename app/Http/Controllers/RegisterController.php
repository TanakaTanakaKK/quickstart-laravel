<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use App\Models\Task;

class RegisterController extends Controller
{
    public function index(Request $request)
    {
        return view('register');
    }

    public function setToken(Request $request)
    {
        $this->validate($request,[
            'email' => 'email:filter,d'
            // テーブル作成後に追加 
            // 'usedEmail' => 'unique:users,email-address'

        ]);
        
        $email = $request->email;

        // emailが既に登録済みか調べる
        return view('register',['flagUsedAddres' => true]);

        // Token発行


        // テスト用処理
        // return view('register',[
        //     'email' => $email
        // ]);
        
    }

    public function checkToken(Request $request,$token)
    {
        //DBのトークンと一致しているか確認
        
        // 一致していなかったらエラー画面

        // 一致していたら登録画面

        return view('signup',[
            'test' => "test"
        ]);
    }
}
