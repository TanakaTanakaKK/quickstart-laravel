<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
#use App\Models\Task;

class RegisterController extends Controller
{
    public function index(Request $request)
    {
        return view('register');
    }

    public function sendMail(Request $request)
    {
        $this->validate($request,[
            'email' => 'email:filter,d'
            //usersテーブルから重複がないかバリデーション 
            ,'email２' => 'unique:users,email'
        ]);

        $tokentable = new Token();
        $email = $request->email;
        $url = 'http://quickstart-laravel.local/register/';
        $token = 'dadawawda';
        $msgTitle = "仮登録完了メッセージ";
        $msgTemplate = "下記URLへ進んでください";


        // emailが既に登録済みか調べる
        //return view('register');

        // Token発行
    

        //メール送信
        
        Mail::send('welcome',[], function($message)
        use($email,$token,$url,$msgTitle,$msgTemplate) {
            $message->to($email)
            ->subject($msgTitle)
            ->text("{$msgTemplate}\n{$url}{$token}");
        });

        

        // テスト用処理
        return view('register');
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
