<?php

namespace App\Http\Controllers;

use App\Models\Token;
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
            //usersテーブルから重複がないかバリデーション 
            ,'email２' => 'unique:users,email'
        ]);
        $tokentable = new Token();
        $email = $request->email;
        $token = null;


        // emailが既に登録済みか調べる
        return view('register');

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
