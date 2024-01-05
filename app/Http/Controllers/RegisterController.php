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
            ->withErrors(['tokenErrors'=>'トークンが無効です。']);
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
                //"before:".date('Y-m-d')
                'before:today'
            ],
            "phone_number" => [
                "required",
                "regex:/^[0-9]{3}-?[0-9]{4}-?[0-9]{4}$/",
                "unique:users,phone_number"
            ],
            "postalcode" => [
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
        $users = new User();
        $token = new Token();
        $email = $token->where('token',$user_token)->value('email');
        $phoneNumber = mb_ereg_replace("-","",$request->phone_number);
        $postalCode = mb_ereg_replace("-","",$request->postalcode);
        try{
            $users->create([
                'name' => $request->name,
                'email' => $email,
                'kana_name'=>$request->kana_name,
                'nickname'=>$request->nickname,
                'gender' => $request->gender,
                'birthday' => $request->birthday,
                'phone_number' => $phoneNumber,
                'postal_code' => $postalCode,
                'prefecture'=>$request->prefecture,
                'city'=>$request->city,
                'block'=>$request->block,
                'building'=>$request->building
            ]);
        }catch(Exception $e){
            $error = $e->errorInfo[2];
            $errorMessage = "会員登録に失敗しました。";
            if(str_contains($error,"email")){
                $errorMessage = "そのメールアドレスは既に使用されています。";
            }elseif(str_contains($error,"phone")){
                $errorMessage = "その電話番号は既に使用されています。";
            }
            return to_route('home')
            ->withErrors(['registerError' => $errorMessage])
            ->withInput();
        }
        $token->where('token',$user_token)->update(['status'=>'会員登録完了']);
        return to_route('register.successful',['name'=>$request->name])
        ->withInput();
    }
    public function registerSuccessful(Request $request)
    {   
        if(!empty($request->input('name'))){
            return view('tasks')
            ->with(['successful' => $request->name.'さんの会員登録が完了しました。']);
        }else{
            return to_route('home');
        }
    }
}
