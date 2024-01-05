<?php
namespace App\Libraries;

use Illuminate\Support\Facades\Facade;
use App\Models\Token;

class CommonFunctions extends Facade
{
    public static function hasToken($user_token) 
    { 
        $token = new Token;
        $token_created_time = $token
            ->where('token',$user_token)
            ->where('status','メール送信完了')
            ->value('created_at');
        $token_created_time = strtotime($token_created_time);
        $now_time = strtotime(date('Y-m-d H:i:s'));
        if(($now_time-$token_created_time)/60 >= 15 || empty($token_created_time)){
            return false;
        }
        return true;
    } 
}
