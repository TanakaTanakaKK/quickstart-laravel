<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginSessionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use App\Models\{
    User,
    LoginSession
};

class LoginSessionController extends Controller
{
    public function create(Request $request)
    {
        return view('login_session.create');
    }

    public function store(LoginSessionRequest $request)
    {
        if(!is_null($request->session('login_session_token'))){
            $request->session()->forget('login_session_token');
        }

        $is_deprecated_login_session_token = true;
        while( $is_deprecated_login_session_token ){
            $login_session_token = Str::random(rand(30,50));
            $is_deprecated_login_session_token = LoginSession::where('token', $login_session_token)->exists();
        }

        LoginSession::create([
            'logged_in_at' => now(),
            'user_id' => User::where('email', $request->email)->first()->id,
            'token' => $login_session_token
        ]);

        $request->session()->put('login_session_token', $login_session_token);

        return to_route('tasks.index');
    }
    
    public function destroy(Request $request)
    {
        $request->session()->forget('login_session_token');

        return to_route('tasks.index');
    }
}
