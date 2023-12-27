<?php

namespace App\Http\Controllers;

use Illuminate\{
    Http\Request,
    Support\Facades\Mail,
    Support\Str
};
use App\{
    Models\Token,
    Enums\Prefectures,
    Enums\Gender
};

class TokenController extends Controller
{
    public function index(Request $request)
    {
        return view('register');
    }
    public function sendMail(Request $request)
    {
        $email = $request->email;
        $this->validate($request,[
            'email' => 'email:filter,d|unique:users,email'
        ]);
        $tokens = new Token();
        $token = Str::random(rand(30,50));
        $url = 'http://quickstart-laravel.local/create_user/';
        $msgTitle = "仮登録完了メッセージ";
        $msgTemplate = "下記URLへ進んでください";
        $tokens->fill([
            'token' => $token,
            'email' => $email,
            'status' => 'メール送信完了'
        ]);
        $tokens->save();
        Mail::send('welcome',[], function($message)
        use($email,$token,$url,$msgTitle,$msgTemplate) {
            $message->to($email)
            ->subject($msgTitle)
            ->text("{$msgTemplate}\n{$url}{$token}");
        });
        return view('register',[
            'email' => $email
        ]);
    }
    public function checkToken(Request $request)
    {
        $tokensTable = new Token();
        $existsTokensList = $tokensTable->pluck('token');
        $canAddUser = false;
        foreach($existsTokensList as $existsToken){
            if($existsToken === $request->token){
                $canAddUser = true;
            }
        }
        if($canAddUser === false){
            return redirect('/tasks')->withErrors(['tokenError' => 'トークンが無効です。'])->withInput();
        }
        return view('create_user');
    }
}
