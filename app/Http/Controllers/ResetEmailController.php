<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResetEmailController extends Controller
{
    public function edit(){
        return view('reset_email.edit');
    }
}
