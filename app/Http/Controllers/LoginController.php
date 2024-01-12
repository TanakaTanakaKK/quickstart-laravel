<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    public function create(Request $request)
    {
        return view('login.create');
    }

    public function store(LoginRequest $request)
    {
        if(!is_null($request->session('user_record'))){
            $request->session()->forget('user_record');
        }
        $request->session()->put('user_record',User::where('email', $request->email)->first());
        return to_route('tasks.index');
    }
    
    public function logout(Request $request)
    {
        $request->session()->put('user_record',User::where('email', $request->email)->first());
        return to_route('tasks.index');
    }
}
