<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;
use App\Models\LoginCredential;
use Carbon\Carbon;

class LoginCredentialMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!is_null(session('login_credential_token')) && LoginCredential::where('token', session('login_credential_token'))->exists()){
            $request->session()->put('login_credential_token', $request->session()->get('login_credential_token'));
        }
        return $next($request);
    }    
    
}