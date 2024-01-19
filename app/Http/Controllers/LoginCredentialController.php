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
        $user = User::where('email', $request->email)->first();
        if(!is_null($user) && !Hash::check($request->password,$user->password)){
            return to_route('login_credential.create')->withErrors(['login_error' => 'パスワードが一致しません']);
        }

        if(!is_null($request->session('login_credential_token'))){
            $request->session()->forget('login_credential_token');
        }

        $is_deprecated_login_credential_token = true;
        while($is_deprecated_login_credential_token){
            $login_credential_token = Str::random(rand(30,50));
            $is_deprecated_login_credential_token = LoginCredential::where('token', $login_credential_token)->exists();
        }

        LoginCredential::create([
            'user_id' => User::where('email', $request->email)->first()->id,
            'token' => $login_credential_token
        ]);

        $request->session()->put('login_credential_token', $login_credential_token);

        return to_route('tasks.index');
    }
    
    public function destroy(Request $request)
    {
        if(is_null($request->session('login_credential_token'))){
            return to_route('tasks.index');
        }

        $request->session()->forget('login_credential_token');

        return to_route('tasks.index');
    }
}
