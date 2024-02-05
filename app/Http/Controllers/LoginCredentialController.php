<?php

namespace App\Http\Controllers;

use App\Models\{
    User,
    LoginCredential
};
use Illuminate\Http\Request;
use App\Http\Requests\LoginCredentialRequest;

class LoginCredentialController extends Controller
{
    public function create(Request $request)
    {
        if(auth()->check()){
            return to_route('task.index');
        }
        return view('login_credential.create');
    }

    public function store(LoginCredentialRequest $request)
    {
        if(auth()->check()){
            auth()->logout();
        }

        $user_id = User::where('email', $request->email)->value('id');

        LoginCredential::create([
            'user_id' => $user_id,
            'agent' => $request->header('User-Agent'),
            'ip' => get_request_ip()
        ]);

        $user = User::where('id', $user_id)->first();

        auth()->login($user);

        return to_route('task.index');
    }
    
    public function destroy(Request $request)
    {
        session()->flush();
        auth()->logout();

        return to_route('login_credential.create');
    }
}