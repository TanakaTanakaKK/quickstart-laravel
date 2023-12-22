<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Name;
use App\Models\Token;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
#use App\Models\Task;
use App\Rules\CheckImg;
use App\Rules\CheckName;
use App\Rules\CheckBirthday;
use App\Rules\CheckPhoneNumber;
use App\Rules\CheckKanaName;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    public function index(Request $request)
    {
        return view('register');
    }

    public function sendMail(Request $request)
    {
        // if($request->has('reset')){
        //     dd($request);
        // }
        $email = $request->email;
        $this->validate($request,[
            'email' => 'email:filter,d|unique:users,email'
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
        //存在しなければトップページへ
        if($flagExists === false){
            return redirect('/');
        }
        // 一致していたら登録画面
        return view('create_user');
    }


    public function resetMail(Request $request)
    {

    }
    public function register(Request $request)
    {
        //tokenの取得
        $url = url()->previous();
        $token = explode("/",$url)[4];
        //tokenのチェック
        $flagExists = false;
        $tokensTable = new Token();
        $existsTokensList = $tokensTable->pluck('token');
        foreach($existsTokensList as $existsToken){
            if($existsToken === $token){
                $flagExists = true;
            }
        }
        if($flagExists === false){
            return redirect('/');
        }

        $this->validate($request,[
            // "user_img" => [
            //     'required',
            //     'file',
            //     'mimes:jpeg,jpg,png',
            //     'dimensions:min_width=100,min_height=100,max_width=500,max_height=500',
            // ]//,new CheckImg()]
            "name" => "required"
            ,"kana_name" => "required"
            ,"nickname" => "required"
            ,"gender" => "required"
            ,"birthday" => "required"
            ,"phone_number" => "required"
            ,"postalcode" => "required"
            ,"prefecture" => "required"
            ,"city" => "required"
            ,"block" => "required"
            ,"building" => ""
        ]);
        // $imgFile = $request->file('user_img');
        // Storage::putFileAs('storage/images/',$imgFile,'public/imgs');
        // dd($imgFile);

        //usersテーブルに入れる変数の定義
        $usersTable = new User();
        $email = $tokensTable->where('token',$token)->value('email');
        $name = $request->name;
        $gender = $request->gender;
        $birthday = $request->birthday;
        $phoneNumber = $request->phone_number;
        $imgPath = 'test';
        //namesテーブル
        $namesTable = new Name();
        $kanaName = $request->kana_name;
        $nickName = $request->nickname;
        //addressテーブル
        $addressesTable = new Address();
        $postalCode = $request->postalcode;
        $prefecture = $request->prefecture;
        $city = $request->city;
        $town = $request->town;
        $block = $request->block;
        $building = $request->building;

        $usersTable->fill([
            'name' => $name
            ,'email' => $email
            ,'gender' => $gender
            ,'birthday' => $birthday
            ,'phone_number' => $phoneNumber
            //,'img_path' => $imgPath
            ,'img_path' => 'test'
        ]);
        $usersTable->save();
        $userId = $usersTable->id;
        $namesTable->fill([
            'user_id'=>$userId
            ,'kana_name'=>$kanaName
            ,'nickname'=>$nickName
        ]);
        $namesTable->save();
        $addressesTable->fill([
            'user_id'=> $userId
            ,'postal_code' => $postalCode
            ,'prefecture'=>$prefecture
            ,'city'=>$city
            ,'town'=>$town
            ,'block'=>$block
            ,'building'=>$building
        ]);
        $addressesTable->save();

        //Tokenレコードの削除
        $tokensTable->where('email',$email)->delete();


        return redirect('/');

    }

    
}
