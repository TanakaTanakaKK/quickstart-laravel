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

        return to_route('password_reset_authentication.complete', $password_reset_token)->with(['is_sent_authentication_email' => true]);
    }

    public function complete(Request $request)
    {
        if(is_null($request->session()->get('is_sent_authentication_email'))){
            return to_route('tasks.index');
        }

        $request->session()->forget('is_sent_authentication_email');

        return view('password_reset.complete', ['is_sent_authentication_email' => true]);
    }
}