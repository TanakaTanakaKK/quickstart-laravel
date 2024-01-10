<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Enums\UserStatus;
use App\Models\Authentication;
use App\Mail\SendTokenMail;
use App\Http\Requests\AuthenticationRequest;
use Carbon\Carbon;

class AuthenticationController extends Controller
{
    public function create(Request $request)
    {
        return view('authentication/create');
    }
    public function store(AuthenticationRequest $request)
    {
        $user_token = Str::random(rand(30, 50));
        $authentication = Authentication::where('email', $request->email)->first();
        if(isset($authentication) && $authentication->status == UserStatus::PENDING){
            $authentication->token = $user_token;
            $authentication->expiration_at = Carbon::now()->addMinutes(15);
            $authentication->save();
        }else{
            Authentication::create([
                'token' => $user_token,
                'email' => $request->email,
                'status' => UserStatus::PENDING,
                'expiration_at' => Carbon::now()->addMinutes(15)
            ]);
        }
        Mail::to($request->email)->send(new SendTokenMail(route('users.create', $user_token)));
        return to_route('authentications.complete', $user_token);
    }
        public function complete(Request $request)
    {
        $authentication = Authentication::where('token', $request->token)->first();
        if(is_null($authentication)){
            return to_route('tasks.index');
        }
        return view('authentication/complete', ['successful' => $authentication['email'].'宛にメールを送信しました。15分以内に登録手続きをしてください。']);
    }
}