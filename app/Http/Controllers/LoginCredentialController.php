<?php

namespace App\Http\Controllers;

use App\Models\{
    User,
    LoginCredential
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Requests\LoginCredentialRequest;

class LoginCredentialController extends Controller
{
    public function create(Request $request)
    {
        return view('login_credential.create');
    }

    public function store(LoginCredentialRequest $request)
    {
        if(!is_null($request->session('login_session_token'))){
            $request->session()->forget('login_session_token');
        }

        $is_duplicated_login_session_token = true;
        while($is_duplicated_login_session_token){
            $login_session_token = Str::random(rand(30,50));
            $is_duplicated_login_session_token = LoginCredential::where('token', $login_session_token)->exists();
        }

        LoginCredential::create([
            'user_id' => User::where('email', $request->email)->value('id'),
            'token' => $login_session_token
        ]);

        $request->session()->put('login_session_token', $login_session_token);

        return to_route('tasks.index');
    }
    
    public function destroy(Request $request)
    {
        if(!is_null($request->session('login_session_token'))){
            $request->session()->forget('login_session_token');
        }

        return to_route('tasks.index');
    }
}
