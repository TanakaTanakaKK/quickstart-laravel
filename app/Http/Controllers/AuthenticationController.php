<?php

namespace App\Http\Controllers;

use App\Enums\{
    AuthenticationStatus,
    AuthenticationType
};
use App\Models\{
    Authentication,
    LoginCredential
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Http\Requests\AuthenticationRequest;
use App\Mail\{
    EmailResetMail,
    SendTokenMail,
    PasswordResetMail
};
use Exception;

class AuthenticationController extends Controller
{
    public function create(Request $request)
    {
        return view('authentication.create', ['authentication_type' => AuthenticationType::USER_REGISTER]);
    }

    public function createPassword(Request $request)
    {
        return view('authentication.create', ['authentication_type' => AuthenticationType::PASSWORD_RESET]);
    }

    public function createEmail(Request $request)
    {
        return view('authentication.create', ['authentication_type' => AuthenticationType::EMAIL_RESET]);
    }

    public function store(AuthenticationRequest $request)
    {
        $is_duplicated_authentication_token = true;
        while($is_duplicated_authentication_token){
            $authentication_token = Str::random(rand(30, 50));
            $is_duplicated_authentication_token = Authentication::where('token', $authentication_token)->exists();
        }

        try{
            $authentication = Authentication::create([
                'token' => $authentication_token,
                'email' => $request->email,
                'status' => AuthenticationStatus::MAIL_SENT,
                'expired_at' => now()->addMinutes(15),
                'type' => $request->authentication_type
            ]);
        }catch(Exception $e){
            return to_route('login_credential.create')->withErrors(['reset_error' => '認証メールの送信に失敗しました。']);
        }

        if((int)$request->authentication_type === AuthenticationType::USER_REGISTER){
            Mail::to($request->email)->send(new SendTokenMail($authentication_token));
            $authentication_message = $request->email.'宛に認証メールを送信しました。15分以内に登録手続きをしてください。';

        }elseif((int)$request->authentication_type === AuthenticationType::PASSWORD_RESET){
            Mail::to($request->email)->send(new PasswordResetMail($authentication_token));
            $authentication_message = $request->email.'宛に認証メールを送信しました。15分以内にパスワードの再設定をしてください。';

        }elseif((int)$request->authentication_type === AuthenticationType::EMAIL_RESET){
            $authentication->user_id = LoginCredential::where('token', $request->session()->get('login_credential_token'))->value('user_id');
            $authentication->save();
            Mail::to($request->email)->send(new EmailResetMail($authentication_token));
            $authentication_message = $request->email.'宛に認証メールを送信しました。15分以内にリンクをクリックしてメールアドレスを変更してください。';
        }else{
            return to_route('login_credential.create')->withErrors(['reset_error' => '認証メールの送信に失敗しました。']);
        }
        
        $request->session()->flash('is_sent_authentication_mail', true);
        $request->session()->flash('authentication_message', $authentication_message);
        return to_route('authentications.complete');
    }

    public function complete(Request $request)
    {
        if(is_null($request->session()->get('is_sent_authentication_mail'))){
            return to_route('tasks.index');
        }

        $authentication_message = $request->session()->get('authentication_message');

        return view('authentication.complete', [
            'is_succeeded' => true,
            'authentication_message' => $authentication_message
        ]);
    }
}