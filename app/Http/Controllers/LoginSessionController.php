<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginSessionRequest;
use Illuminate\Http\Request;
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
        if(!is_null($request->session('user_record'))){
            $request->session()->forget('user_record');
        }

        $user =  User::where('email', $request->email)->first();
        LoginSession::create([
            'logged_in_at' => now(),
            'user_id' => $user->id
        ]);

        $request->session()->put('user_record', $user);
        return to_route('tasks.index');
    }
    
    public function destroy(Request $request)
    {
        if(is_null($request->session('user_record'))){
            return to_route('tasks.index');
        }

        $request->session()->forget('user_record');

        return to_route('tasks.index');
    }
}
