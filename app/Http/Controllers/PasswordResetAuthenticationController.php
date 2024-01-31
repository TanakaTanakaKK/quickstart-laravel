<?php

namespace App\Http\Controllers;

use App\Enums\PasswordResetStatus;
use App\Models\{
    PasswordResetAuthentication,
    User
};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;
use App\Http\Requests\UserEmailRequest;

class PasswordResetAuthenticationController extends Controller
{
    public function create(Request $request)
    {
        return view('password_reset_authentication.create');
    }

    public function store(UserEmailRequest $request)
    {
        $is_duplicated_password_reset_token = true;
        while($is_duplicated_password_reset_token){
            $password_reset_token = Str::random(rand(30, 50));
            $is_duplicated_password_reset_token = PasswordResetAuthentication::where('token', $password_reset_token)->exists();
        }

        PasswordResetAuthentication::create([
            'user_id' => User::where('email', $request->email)->value('id'),
            'token' => $password_reset_token,
            'status' => PasswordResetStatus::MAIL_SENT,
            'expired_at' => now()->addMinute(15)
        ]);

        Mail::to($request->email)->send(new PasswordResetMail($password_reset_token));

        return to_route('password_reset_authentication.complete')->with(['is_sent_authentication_email' => true]);
    }

    public function complete(Request $request)
    {
        if(!is_null($request->session()->get('is_sent_authentication_email'))){
            $request->session()->forget('is_sent_authentication_email');
            $password_reset_message = '認証メールを送信しました。15分以内に登録手続きをしてください。';
        }elseif(!is_null($request->session()->get('is_password_reset'))){
            $request->session()->forget('is_password_reset');
            $password_reset_message = 'パスワードの変更が完了しました。';
        }else{
            return to_route('tasks.index');
        }


        return view('password_reset_authentication.complete', ['password_reset_message' => $password_reset_message]);
    }
}