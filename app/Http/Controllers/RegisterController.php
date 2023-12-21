<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
#use App\Models\Task;

class RegisterController extends Controller
{
    public function index(Request $request)
    {
        return view('register');
    }

    public function sendMail(Request $request)
    {
        $email = $request->email;
        $this->validate($request,[
            'email' => 'email:filter,d|unique:users,email|unique:tokens,email' 
        ]);

        $tokentable = new Token();

        $url = 'http://quickstart-laravel.local/register/';
        $msgTitle = "仮登録完了メッセージ";
        $msgTemplate = "下記URLへ進んでください";

        //return view('register');

        // Token発行
        $token = Str::random(rand(10,30));

        //DB登録
        $tokentable->fill([
            'token' => $token
            ,'email' => $email
        ]);
        $tokentable->save();

        //メール送信
        
        Mail::send('welcome',[], function($message)
        use($email,$token,$url,$msgTitle,$msgTemplate) {
            $message->to($email)
            ->subject($msgTitle)
            ->text("{$msgTemplate}\n{$url}{$token}");
        });

        

        // テスト用処理
        return view('register',[
            'email' => $email
        ]);
        
    }

    public function resetMail(Request $request)
    {

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
