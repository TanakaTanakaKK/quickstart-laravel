<?php

namespace App\Http\Middleware;

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
        $login_credential_token = session('login_credential_token');

        if(is_null($login_credential_token) && !LoginCredential::where('token', $login_credential_token)->exists()){
            return to_route('login_credential.create');
        }

        $request->session()->put('login_credential_token', $request->session()->get('login_credential_token'));

        return $next($request);
    }    
    
}
