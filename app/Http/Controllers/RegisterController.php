<?php

namespace App\Http\Controllers;

use App\Models\{
    Token,
    User
};
use Illuminate\{
    Http\Request,
};
use App\Rules\{
    CheckName,
    CheckBirthday,
    CheckBlock,
    CheckCity,
    CheckPhoneNumber,
    CheckKanaName,
    CheckPostalCode,
    CheckPrefecture
};
use Exception;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $token = explode('/',$request->headers->get("referer"))[4];
        $canAddUser = false;
        $tokensTable = new Token();
        $existsTokensList = $tokensTable->pluck('token');
        $canAddUser = false;
        foreach($existsTokensList as $existsToken){
            if($existsToken === $token){
                $canAddUser = true;
            }
        }
        if($canAddUser === false){
            return redirect('/tasks')->withErrors(['tokenError' => 'トークンが無効です。'])->withInput();
        }
        $this->validate($request,[
            "name" => ["required",new CheckName()],
            "kana_name" => ["required",new CheckKanaName(),"regex:/^[^#<>^;_]*$/"],
            "nickname" => "required",
            "gender" => "required",
            "birthday" => ["required",new CheckBirthday()],
            "phone_number" => ["required",new CheckPhoneNumber()],
            "postalcode" => ["required",new CheckPostalCode()],
            "prefecture" => ["required",new CheckPrefecture()],
            "city" => ["required",new CheckCity()],
            "block" => ["required",new CheckBlock()],
        ]);
        $usersTable = new User();
        $email = $tokensTable->where('token',$token)->value('email');
        $name = $request->name;
        $kanaName = $request->kana_name;
        $nickName = $request->nickname;
        $gender = $request->gender;
        $birthday = $request->birthday;
        $phoneNumber = $request->phone_number;
        $phoneNumber = mb_ereg_replace("ー","",$phoneNumber);
        $phoneNumber = mb_ereg_replace("－","",$phoneNumber);
        $phoneNumber = mb_ereg_replace("-","",$phoneNumber);
        $phoneNumber = mb_convert_kana($phoneNumber,'a','UTF-8');
        $phoneNumber = preg_replace('/[\x21-\x2f|\x3a-\x40|\x5b-\x60|\x7b-\x7e]+/', '', $phoneNumber);
        $postalCode = $request->postalcode;
        $postalCode = mb_ereg_replace("ー","",$postalCode);
        $postalCode = mb_ereg_replace("－","",$postalCode);
        $postalCode = mb_ereg_replace("-","",$postalCode);
        $postalCode = mb_convert_kana($postalCode,'a','UTF-8');
        $postalCode = preg_replace('/[\x21-\x2f|\x3a-\x40|\x5b-\x60|\x7b-\x7e]+/', '', $postalCode);
        $prefecture = $request->prefecture;
        $city = $request->city;
        $town = $request->town;
        $block = $request->block;
        $FirstReplaceList = array("丁目","丁","ー","－");
        $SecondReplaceList = array("番地","番");
        $block = str_replace($FirstReplaceList,'-',$block);
        $block = str_replace($SecondReplaceList,'',$block);
        $block = mb_convert_kana($block,'a','UTF-8');
        $building = $request->building;
        if(isset($building)){
            $building = mb_convert_kana($building,'a','UTF-8');
        }
        try{
            $usersTable->fill([
                'name' => $name,
                'email' => $email,
                'kana_name'=>$kanaName,
                'nickname'=>$nickName,
                'gender' => $gender,
                'birthday' => $birthday,
                'phone_number' => $phoneNumber,
                'postal_code' => $postalCode,
                'prefecture'=>$prefecture,
                'city'=>$city,
                'town'=>$town,
                'block'=>$block,
                'building'=>$building
            ]);
            $usersTable->save();
        }catch(Exception $e){
            $error = $e->errorInfo[2];
            $errorMessage = "会員登録に失敗しました。";
            if(str_contains($error,"email")){
                $errorMessage = "そのメールアドレスは既に使用されています。";
            }elseif(str_contains($error,"phone")){
                $errorMessage = "その電話番号は既に使用されています。";
            }
            return redirect('/tasks')->withErrors(['registerError' => $errorMessage])->withInput();
        }
        $tokensTable->where('email',$email)->delete();
        return view('/tasks',[
            'successful' => '会員登録が完了しました。'
        ]);
    }    
}
