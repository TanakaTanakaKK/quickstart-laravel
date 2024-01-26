<?php

namespace App\Http\Controllers;

use App\Enums\ResetEmailStatus;
use App\Models\ResetEmail;
use Illuminate\Http\Request;
use Exception;

class ResetEmailController extends Controller
{
    public function edit(Request $request){

        $reset_email = ResetEmail::where('token', request()->reset_email_token)
            ->where('status', ResetEmailStatus::MAIL_SENT)
            ->where('expired_at', '>', now())
            ->first();

        if(is_null($reset_email)){
            return to_route('login_credential.create')->withErrors(['token_error' => 'トークンが無効です。']);
        }

        return view('reset_email.edit',[
            'before_email' => $reset_email->user->email,
            'after_email' => $reset_email->email
        ]);
    }

    public function update(Request $request){

        $reset_email = ResetEmail::where('token', $request->reset_email_token)
            ->where('status', ResetEmailStatus::MAIL_SENT)
            ->where('expired_at', '>', now())
            ->first();

        if(is_null($reset_email)){
            return to_route('login_credential.create')->withErrors(['token_error' => 'トークンが無効です。']);
        }

        try{
            $reset_email->status = ResetEmailStatus::COMPLETED;
            $reset_email->user->email = $reset_email->email;
            $reset_email->push();
        }catch(Exception $e){
            return to_route('login_credential.create')->withErrors('更新に失敗しました。');
        }

        return to_route('reset_email.complete', $request->reset_email_token)->with(['is_updated_email' => true]);
    }

    public function complete(Request $request)
    {
        if(is_null($request->session()->get('is_updated_email'))){
            return to_route('user.show');
        }

        $request->session()->forget('is_updated_email');

        return view('user.show', [
            'user_info' => ResetEmail::where('token', $request->reset_email_token)->first()->user,
            'is_succeeded' => true,
            'is_updated_email' => true
        ]);
    }
}
