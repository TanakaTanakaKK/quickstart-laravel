<?php

namespace App\Http\Controllers;

use App\Models\{
    User,
    LoginCredentials
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Requests\LoginCredentialsRequest;

class LoginCredentialsController extends Controller
{
    public function create(Request $request)
    {
        return view('login_credentials.create');
    }

    public function store(LoginCredentialsRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if(!is_null($user) && !Hash::check($request->password,$user->password)){
            return to_route('login_credentials.create')->withErrors(['login_error' => 'パスワードが一致しません']);
        }

        if(!is_null($request->session('login_session_token'))){
            $request->session()->forget('login_session_token');
        }

        $is_duplicated_login_session_token = true;
        while($is_duplicated_login_session_token){
            $login_session_token = Str::random(rand(30,50));
            $is_duplicated_login_session_token = LoginCredentials::where('token', $login_session_token)->exists();
        }

        LoginCredentials::create([
            'logged_in_at' => now(),
            'user_id' => $user->id,
            'token' => $login_session_token
        ]);

        $request->session()->put('login_session_token', $login_session_token);

        return to_route('tasks.index');
    }
    
    public function destroy(Request $request)
    {
        if(is_null($request->session('login_session_token'))){
            return to_route('tasks.index');
        }

        $request->session()->forget('login_session_token');

        return to_route('tasks.index');
    }
}
