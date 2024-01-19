<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
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
        if(!is_null(session('login_session_token')) && LoginCredential::where('token', session('login_session_token'))->exists()){
            $request->session()->put('login_session_token', $request->session()->get('login_session_token'));
        }
        return $next($request);
    }
}
