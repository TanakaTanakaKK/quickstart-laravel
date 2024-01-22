<?php

namespace App\Http\Controllers;

use App\Enums\ResetPasswordStatus;
use App\Models\{
    ResetPassword,
    User
};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    Hash,
    Mail
};
use App\Http\Requests\{
    ResetNewPasswordRequest,
    UserEmailRequest
};
use App\Mail\ResetPasswordMail;

class ResetPasswordController extends Controller
{
    public function create(Request $request)
    {
        return view('reset_password.create');
    }

    public function store(UserEmailRequest $request)
    {
        if(is_null(User::Where('email', $request->email)->first())){
            return to_route('tasks.index');
        }
        $is_duplicated_reset_password_token = true;
        while($is_duplicated_reset_password_token){
            $reset_password_token = Str::random(rand(30, 50));
            $is_duplicated_reset_password_token = ResetPassword::where('token', $reset_password_token)->exists();
        }

        ResetPassword::create([
            'email' => $request->email,
            'token' => $reset_password_token,
            'status' => ResetPasswordStatus::MAIL_SENT,
            'expired_at' => now()->addMinute(15)
        ]);

        Mail::to($request->email)->send(new ResetPasswordMail($reset_password_token));

        return to_route('reset_password.complete', $reset_password_token)->with(['is_reset_mail_send' => true]);
    }

    public function edit(Request $request)
    {
        $reset_password = ResetPassword::where('token', $request->reset_password_token)
            ->where('status', ResetPasswordStatus::MAIL_SENT)
            ->where('expired_at', '>', now())
            ->first();
        if(is_null($reset_password)){
            return to_route('tasks.index')->withErrors(['reset_error' => '無効なアクセスです。']);
        }

        return view('reset_password.edit');
    }

    public function update(ResetNewPasswordRequest $request)
    {
        $reset_password = ResetPassword::where('token', $request->reset_password_token)
            ->where('status', ResetPasswordStatus::MAIL_SENT)
            ->where('expired_at', '>', now())
            ->first();
        
        if(is_null($reset_password)){
            return to_route('tasks.index')->withErrors(['reset_error' => '無効なアクセスです。']);
        }

        $reset_password->status = ResetPasswordStatus::COMPLETED;
        $reset_password->users->password = Hash::make($request->password);
        $reset_password->push();

        return to_route('reset_password.complete', $request->reset_password_token)->with(['is_password_updated' => true]);
    }

    public function complete(Request $request)
    {
        $reset_password = ResetPassword::where('token', $request->reset_password_token)->first();

        if(is_null($reset_password) && is_null($request->session()->get('is_reset_mail_send') || is_null($request->session()->get('is_password_updated')))){
            return to_route('tasks.index');
        }

        $complete_message = match($reset_password->status){
            ResetPasswordStatus::MAIL_SENT => ['reset_password_email' => $reset_password->email],
            ResetPasswordStatus::COMPLETED => ['successful' => 'ログインパスワードの変更が完了しました。'],
            default => null
        };
        
        $request->session()->forget('is_reset_mail_send'); 
        $request->session()->forget('is_password_updated');

        if(!is_null($complete_message)){
            return view('reset_password.complete')->with($complete_message);
        }
    
        return to_route('tasks.index');
        
    }
}
