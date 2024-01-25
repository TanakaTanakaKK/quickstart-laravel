<?php

namespace App\Http\Middleware;

use App\Enums\UserStatus;
use App\Enums\Prefecture;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;
use App\Models\LoginCredential;

class LoginVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $login_credential = LoginCredential::where('token', session('login_credential_token'))->first();
        if(!is_null(session('login_credential_token')) && !is_null($login_credential)){
            if($login_credential->user->status == UserStatus::ADMIN){
                $request->merge(['user_status' => UserStatus::ADMIN]);
            }
            $request->session()->put('login_credential_token', $request->session()->get('login_credential_token'));
        }else{
            return to_route('login_credential.create');
        }
        return $next($request);
    }    
    
}
