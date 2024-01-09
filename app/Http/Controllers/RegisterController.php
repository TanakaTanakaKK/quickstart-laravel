<?php

namespace App\Http\Controllers;
use App\{
    Models\Token,
    Models\User,
    Libraries\CommonFunctions,
    Http\Requests\RegisterRequest
};
use Illuminate\{
    Http\Request,
};
use Exception;
class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {   
        $user_token = explode('/',$request->headers->get('referer'))[4];
        // if(!CommonFunctions::hasToken($user_token)){
        //     return to_route('home')
        //     ->withErrors(['token_error' => 'トークンが無効です。']);
        // }
        $phone_number = mb_ereg_replace("-","",$request->phone_number);
        $postal_code = mb_ereg_replace("-","",$request->postal_code);
        try{
            User::create([
                'name' => $request->name,
                'email' => Token::where('token',$user_token)->value('email'),
                'kana_name'=>$request->kana_name,
                'nickname'=>$request->nickname,
                'gender' => $request->gender,
                'birthday' => $request->birthday,
                'phone_number' => $phone_number,
                'postal_code' => $postal_code,
                'prefecture'=>$request->prefecture,
                'city'=>$request->city,
                'block'=>$request->block,
                'building'=>$request->building
            ]);
        }catch(Exception $e){
            return to_route('home')
            ->withErrors(['register_error' => "会員登録に失敗しました。"]);
        }
        Token::where('token',$user_token)->update(['status' => '会員登録完了']);
        return to_route('register.successful',['name' => $request->name]);
    }
    public function registerSuccessful(Request $request)
    {   
        if($request->name){
            return view('successful',['successful' => $request->name.'さんの会員登録が完了しました。']);
        }
        return to_route('home');
    }
}