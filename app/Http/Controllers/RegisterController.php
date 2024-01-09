<?php

namespace App\Http\Controllers;
use App\{
    Models\Token,
    Models\User,
    Libraries\CommonFunctions
};
use Illuminate\{
    Http\Request,
};
use Exception;
class RegisterController extends Controller
{
    public function register(Request $request)
    {   
        $user_token = explode('/',$request->headers->get('referer'))[4];
        if(!CommonFunctions::hasToken($user_token)){
            return to_route('home')
            ->withErrors(['token_error' => 'トークンが無効です。']);
        }
        $request->validate([
            "name" => [
                "required",
                "regex:/^[ぁ-んァ-ヶ一-龠]+$/u"
            ],
            "kana_name" => [
                "required",
                "regex:/^[ァ-ヶ]+$/u"
            ],
            "nickname" => "required",
            "gender" => "required",
            "birthday" => [
                "required",
                'before:today'
            ],
            "phone_number" => [
                "required",
                "regex:/^[0-9]{3}-?[0-9]{4}-?[0-9]{4}$/",
                "unique:users,phone_number"
            ],
            "postal_code" => [
                "required",
                "regex:/^[0-9]{3}-?[0-9]{4}$/"
            ],
            "prefecture" => [
                "required",
                "regex:/^[一-龠]+[都|道|府|県]$/u"
            ],
            "city" => [
                "required",
                "regex:/^[ぁ-んァ-ヶ一-龠]+$/u"
            ],
            "block" => [
                "required",
                "regex:/^[0-9]+-?[0-9]+?-?[0-9]+?$/"
            ],
            "building" => [
                'nullable',
                'regex:/^[ぁ-んァ-ヶ一-龠a-zA-Z0-9p{L}\p{N}\-ー〜 ]+$/u'
            ]
        ]);
        $user = new User();
        $token = new Token();
        $phone_number = mb_ereg_replace("-","",$request->phone_number);
        $postal_code = mb_ereg_replace("-","",$request->postal_code);
        try{
            $user->create([
                'name' => $request->name,
                'email' => $token->where('token',$user_token)->value('email'),
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
        $token->where('token',$user_token)->update(['status' => '会員登録完了']);
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