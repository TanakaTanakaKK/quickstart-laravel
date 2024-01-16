<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    Hash,
    Mail
};
use App\Models\{
    ResetPassword,
    User
};
use App\Mail\ResetPasswordMail;
use App\Enums\ResetPasswordStatus;
use App\Http\Requests\ResetPasswordRequest;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function create(Request $request)
    {
        return view('reset_password.create');
    }

    public function store(ResetPasswordRequest $request)
    {
        if(is_null(User::Where('email', $request->email)->first())){
            return to_route('tasks.index');
        }
        $is_deprecated_reset_password_token = true;
        while($is_deprecated_reset_password_token){
            $reset_password_token = Str::random(rand(30, 50));
            $is_deprecated_reset_password_token = ResetPassword::where('token', $reset_password_token)->exists();
        }

        $reset_password = ResetPassword::where('email', $request->email)
            ->where('status', ResetPasswordStatus::COMPLETED)
            ->first();

        if(!is_null($reset_password)){
            $reset_password->token = $reset_password_token;
            $reset_password->expired_at = Carbon::now()->addMinute(15);
            $reset_password->save();
        }else{
            ResetPassword::create([
                'email' => $request->email,
                'token' => $reset_password_token,
                'status' => ResetPasswordStatus::MAIL_SENT,
                'expired_at' => Carbon::now()->addMinute(15)
            ]);
        }

        Mail::to($request->email)->send(new ResetPasswordMail($reset_password_token));

        return to_route('reset_password.complete', $reset_password_token)->with(['is_reset_mail_send' => true]);
    }

    public function edit(Request $request)
    {
        $reset_password = ResetPassword::where('token', $request->reset_password_token)->first();

        if(is_null($reset_password) || $reset_password->status == ResetPasswordStatus::COMPLETED){
            return to_route('tasks.index');
        }

        return view('reset_password.edit');
    }

    public function update(Request $request)
    {
        $reset_password = ResetPassword::where('token', $request->reset_password_token)
            ->where('status', ResetPasswordStatus::MAIL_SENT)
            ->where('expired_at', '>', Carbon::now())
            ->first();
        
        if(is_null($reset_password)){
            return to_route('tasks.index')->withErrors(['reset_error' => 'リセットトークンが無効です。']);
        }

        $reset_password->status = ResetPasswordStatus::COMPLETED;
        $reset_password->save();
        
        $user = User::where('email', $reset_password->email)->first();

        $user->password = Hash::make($request->password);
        $user->save();

        return to_route('reset_password.complete',$request->reset_password_token)->with(['is_password_updated' => true]);
    }

    public function complete(Request $request)
    {
        $reset_password = ResetPassword::where('token', $request->reset_password_token)
        ->orderBy('updated_at', 'desc')
        ->first();

        if(is_null($reset_password) || is_null($request->session()->get('is_reset_mail_send'))){
            return to_route('tasks.index');
        }else if($request->session()->get('is_reset_mail_send') == true && $reset_password->status == ResetPasswordStatus::MAIL_SENT){
            $request->session()->forget('is_reset_mail_send');
            return view('reset_password.complete')->with(['reset_password_email' => $reset_password->email]);
        }else if($request->session()->get('is_password_updated') == true && $reset_password->status == ResetPasswordStatus::COMPLETED){
            $request->session()->forget('is_password_updated');
            return view('reset_password.complete')->with(['successful' => 'ログインパスワードの変更が完了しました。']);
        }else{
            return to_route('tasks.index');
        }
    }
}
