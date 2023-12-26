<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Rules\CheckImg;
use App\Rules\CheckName;
use App\Rules\CheckBirthday;
use App\Rules\CheckBlock;
use App\Rules\CheckCity;
use App\Rules\CheckPhoneNumber;
use App\Rules\CheckKanaName;
use App\Rules\checkPostalCode;
use App\Rules\CheckPrefecture;
use Exception;
use Illuminate\Support\Facades\Storage;

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
            'email' => 'email:filter,d|unique:users,email'
        ]);

        $tokentable = new Token();
        $token = Str::random(rand(30,50));
        $url = 'http://quickstart-laravel.local/create_user/';
        $msgTitle = "仮登録完了メッセージ";
        $msgTemplate = "下記URLへ進んでください";
        $tokentable->fill([
            'token' => $token,
            'email' => $email
        ]);
        $tokentable->save();
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
        $flagExists = false;
        foreach($existsTokensList as $existsToken){
            if($existsToken === $request->token){
                $flagExists = true;
            }
        }
        if($flagExists === false){
            return redirect('/tasks')->withErrors(['tokenError' => 'トークンが無効です。'])->withInput();
        }
        return view('create_user');

    }

    public function register(Request $request)
    {
        $url = url()->previous();
        $token = explode("/",$url)[4];
        $flagExists = false;
        $tokensTable = new Token();


        $existsTokensList = $tokensTable->pluck('token');
        $flagExists = false;
        foreach($existsTokensList as $existsToken){
            if($existsToken === $token){
                $flagExists = true;
            }
        }
        if($flagExists === false){
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
        $addressesTable = new Address();
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
                'phone_number' => $phoneNumber

            ]);
            $usersTable->save();

            $userId = $usersTable->id;

            $addressesTable->fill([
                'user_id'=> $userId,
                'postal_code' => $postalCode,
                'prefecture'=>$prefecture,
                'city'=>$city,
                'town'=>$town,
                'block'=>$block,
                'building'=>$building
            ]);
            $addressesTable->save();
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
