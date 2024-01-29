<?php

namespace App\Http\Middleware;

use App\Enums\UserStatus;
use App\Models\{
    LoginCredential,
    User
};
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

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

        if(is_null(session('login_credential_token')) || is_null($login_credential) || !auth()->check()){
            session()->flush();
            return to_route('login_credential.create');
        }
        $request->session()->put('login_credential_token', $request->session()->get('login_credential_token'));

        return $next($request);
    }    
}
