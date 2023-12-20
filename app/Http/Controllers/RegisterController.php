<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use App\Models\Task;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            'email' => 'email:filter,d'
        ]);
        
        $email = $request->email;

        return view('register',[
            'email' => $email
        ]);
    }

    public function register(Request $request)
    {
        return view('/register');
    }
}
