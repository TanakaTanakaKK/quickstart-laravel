<?php

namespace App\Http\Controllers;

use App\Models\{
    User,
    LoginCredential
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Http\Requests\LoginCredentialRequest;

class LoginCredentialController extends Controller
{
    public function create(Request $request)
    {
        if(!is_null($request->session()->get('login_credential_token'))){
            return to_route('task.index');
        }
        return view('login_credential.create');
    }

    public function store(LoginCredentialRequest $request)
    {
        if(!is_null($request->session('login_credential_token'))){
            $request->session()->forget('login_credential_token');
        }

        $is_duplicated_login_credential_token = true;
        while($is_duplicated_login_credential_token){
            $login_credential_token = Str::random(rand(30, 50));
            $is_duplicated_login_credential_token = LoginCredential::where('token', $login_credential_token)->exists();
        }

        LoginCredential::create([
            'user_id' => User::where('email', $request->email)->value('id'),
            'token' => $login_credential_token,
            'agent' => $request->header('User-Agent'),
            'ip' => $request->ip()
        ]);

        $request->session()->put('login_credential_token', $login_credential_token);

        return to_route('task.index');
    }
    
    public function destroy(Request $request)
    {
        if(!is_null($request->session()->get('login_credential_token'))){
            $request->session()->forget('login_credential_token');
        }

        Cache::forget('weather_info');

        return to_route('login_credential.create');
    }
}
