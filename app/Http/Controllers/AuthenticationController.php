<?php

namespace App\Http\Controllers;

use App\Enums\AuthenticationStatus;
use App\Models\Authentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Http\Requests\AuthenticationRequest;
use App\Mail\SendTokenMail;

class AuthenticationController extends Controller
{
    public function create(Request $request)
    {
        return view('authentication.create');
    }

    public function store(AuthenticationRequest $request)
    {
        $user_token = Str::random(rand(30, 50));
        $authentication = Authentication::where('email', $request->email)
            ->where('status', AuthenticationStatus::MAIL_SENT)
            ->first();

        $expired_at = now()->addMinutes(15);

        if(!is_null($authentication)){
            $authentication->token = $user_token;
            $authentication->expired_at = $expired_at;
            $authentication->save();
        }else{
            Authentication::create([
                'token' => $user_token,
                'email' => $request->email,
                'status' => AuthenticationStatus::MAIL_SENT,
                'expired_at' => $expired_at
            ]);
        }

        Mail::to($request->email)->send(new SendTokenMail($user_token));

        return to_route('authentications.complete')->with(['is_authentication_created' => true]);
    }

    public function complete(Request $request)
    {
        if(is_null($request->session()->get('is_authentication_created'))){
            return to_route('tasks.index');
        }

        $request->session()->forget('is_authentication_created');
        
        return view('authentication.complete')->with(['is_sent_authentication_email' => true]);
    }
}