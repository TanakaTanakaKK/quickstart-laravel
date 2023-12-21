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
        if($request->has('reset')){
            dd($request);
        }
        $email = $request->email;
        $this->validate($request,[
            'email' => 'email:filter,d|unique:users,email|unique:tokens,email'
        ]);

        $tokentable = new Token();
        $token = Str::random(rand(30,50));
        $url = 'http://quickstart-laravel.local/create_user/';
        $msgTitle = "仮登録完了メッセージ";
        $msgTemplate = "下記URLへ進んでください";

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

    public function checkToken(Request $request,$token)
    {
        //DBのトークンと一致しているか確認
        $tokensTable = new Token();
        $existsTokensList = $tokensTable->pluck('token');
        $flagExists = false;
        
        foreach($existsTokensList as $existsToken){
            if($existsToken === $token){
                $flagExists = true;
            }
        }
        // 存在しなければトップページへ
        if($flagExists === false){
            return redirect('/');
        }
        
        //メールアドレスの取得
        $email = $tokensTable->where('token',$token)->value('email');
        //Tokenレコードの削除
        $tokensTable->where('token',$token)->delete();

        // 一致していたら登録画面
        return view('create_user',[
            'email' => $email
        ]);
    }


    public function resetMail(Request $request)
    {

    }
}
