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
            return to_route('tasks.index')->withErrors(['token_error' => 'トークンが無効です。']);
        }

        return view('reset_email.edit',[
            'before_email' => $reset_email->users->email,
            'after_email' => $reset_email->email
        ]);
    }

    public function update(Request $request){

        $reset_email = ResetEmail::where('token', $request->reset_email_token)
        ->where('status', ResetEmailStatus::MAIL_SENT)
        ->where('expired_at', '>', now())
        ->first();

        if(is_null($reset_email)){
            return to_route('tasks.index')->withErrors(['token_error' => 'トークンが無効です。']);
        }

        try{
            $reset_email->status = ResetEmailStatus::COMPLETED;
            $reset_email->users->email = $reset_email->email;
            $reset_email->push();
        }catch(Exception $e){
            return to_route('tasks.index')->withErrors('更新に失敗しました。');
        }

        return to_route('reset_email.complete', $request->reset_email_token)->with(['is_mail_reset_complete' => true]);
    }

    public function complete(Request $request)
    {
        if(is_null($request->session()->get('is_mail_reset_complete'))){
            return to_route('user.show');
        }

        $request->session()->forget('is_mail_reset_complete');

        return view('user.show', [
            'user_info' => ResetEmail::where('token', $request->reset_email_token)->first()->users,
            'is_succeeded' => true,
            'is_completed_reset_email' => true
        ]);
    }
}
