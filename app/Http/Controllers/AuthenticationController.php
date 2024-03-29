<?php

namespace App\Http\Controllers;

use App\Enums\{
    AuthenticationStatus,
    AuthenticationType
};
use App\Models\Authentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Http\Requests\AuthenticationRequest;
use App\Mail\{
    SendTokenMail,
    PasswordResetMail
};
use Exception;

class AuthenticationController extends Controller
{
    public function create(Request $request)
    {
        $authentication_type = (int)$request->authentication_type;
        
        if (!in_array($authentication_type, AuthenticationType::getValues())) {
            to_route('login_credential.create');
        }
        
        return view('authentication.create', [
            'authentication_type' => $authentication_type,
        ]);
    }

    public function store(AuthenticationRequest $request)
    {
        $is_duplicated_authentication_token = true;
        while($is_duplicated_authentication_token){
            $authentication_token = Str::random(rand(30, 50));
            $is_duplicated_authentication_token = Authentication::where('token', $authentication_token)->exists();
        }

        try{
            Authentication::create([
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
        }else{
            return to_route('login_credential.create')->withErrors(['reset_error' => '認証メールの送信に失敗しました。']);
        }

        return to_route('authentications.complete')->with([
            'is_sent_authentication_mail' => true,
            'authentication_message' => $authentication_message
        ]);
    }

    public function complete(Request $request)
    {
        if(is_null($request->session()->get('is_sent_authentication_mail'))){
            return to_route('tasks.index');
        }

        $request->session()->forget('is_sent_authentication_mail');
        $authentication_message = $request->session()->get('authentication_message');
        $request->session()->forget('authentication_message');
        
        return view('authentication.complete', [
            'is_succeeded' => true,
            'authentication_message' => $authentication_message
        ]);
    }
}