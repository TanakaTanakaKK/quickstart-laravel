<?php

namespace App\Http\Controllers;

use Illuminate\{
    Http\Request,
    Support\Facades\Mail,
    Support\Str
};
use App\{
    Models\Token,
    Mail\SendTokenMail
};

class TokenController extends Controller
{
    public function index(Request $request)
    {
        return view('register');
    }
    public function sendMail(Request $request)
    {
        $this->validate($request,[
            'email' => 'email:filter|unique:users,email'
        ]);
        $tokens = new Token();
        $token = Str::random(rand(30,50));
        $tokens->fill([
            'token' => $token,
            'email' => $request->email,
            'status' => 'メール送信完了'
        ]);
        $tokens->save();
        Mail::to($request->email)->send(new SendTokenMail(route('create.user')."/".$token));
        return view('register',[
            'email' => $request->email
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
