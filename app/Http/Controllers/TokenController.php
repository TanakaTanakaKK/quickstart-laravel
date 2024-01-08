<?php

namespace App\Http\Controllers;

use Illuminate\{
    Http\Request,
    Support\Facades\Mail,
    Support\Str
};
use App\{
    Models\Token,
    Mail\SendTokenMail,
    Libraries\CommonFunctions
};
class TokenController extends Controller
{
    public function index(Request $request)
    {
        return view('register');
    }
    public function sendMail(Request $request)
    {
        $request->validate([
            'email' => [
                'email:filter',
                'unique:users,email'
            ]
        ]);
        $token = new Token();
        $user_token = Str::random(rand(30,50));
        if($token->where('email',$request->email)->exists()){
            $token->where('email',$request->email)->update(['token'=>$user_token]);
        }else{
            $token->create([
                'token' => $user_token,
                'email' => $request->email,
                'status' => 'メール送信完了'
            ]);
        }
        Mail::to($request->email)->send(new SendTokenMail(route('token.hasToken',$user_token)));
        return to_route('token.successful',['email' => $request->email]);
    }
        public function tokenSuccessful(Request $request)
    {
        if($request->email){
        return view('register',['successful' => $request->email."宛にメールを送信しました。15分以内に登録手続きをしてください。"]);
        }
        return to_route('home');
    }
    public function hasToken(Request $request)
    {
        if(CommonFunctions::hasToken($request->token)){
            return view('create_user');
        }
        return to_route('home')->withErrors(['token_error' => 'トークンが無効です。']);
    }
}
