<?php

namespace App\Http\Controllers;

use App\Enums\{
    AuthenticationStatus,
    AuthenticationType
};
use App\Http\Middleware\Authenticate;
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
        return view('authentication.create', ['authentication_type' => $request->query('type')]);
    }

    public function store(AuthenticationRequest $request)
    {
        

        $is_duplicated_authentication_token = true;
        while($is_duplicated_authentication_token){
            $authentication_token = Str::random(rand(30, 50));
            $is_duplicated_authentication_token = Authentication::where('token', $authentication_token)->exists();
        }

        $authentication = Authentication::where('email', $request->email)
            ->where('status', AuthenticationStatus::MAIL_SENT)
            ->first();

        $expired_at = now()->addMinutes(15);

        if(!is_null($authentication)){
            $authentication->token = $authentication_token;
            $authentication->expired_at = $expired_at;
            $authentication->save();
        }else{
            Authentication::create([
                'token' => $authentication_token,
                'email' => $request->email,
                'status' => AuthenticationStatus::MAIL_SENT,
                'expired_at' => $expired_at,
                'type' => $request->type
            ]);
        }

        Mail::to($request->email)->send(new SendTokenMail($authentication_token));

        return to_route('authentications.complete')->with(['is_authentication_created' => true]);
    }

    public function complete(Request $request)
    {
        if(is_null($request->session()->get('is_authentication_created'))){
            return to_route('tasks.index');
        }

        $request->session()->forget('is_authentication_created');
                    
        return view('authentication.complete', [
            'is_succeeded' => true,
            'is_sent_authentication_email' => true
        ]);
    }
}