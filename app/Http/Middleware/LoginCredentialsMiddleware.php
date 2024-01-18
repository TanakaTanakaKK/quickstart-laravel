<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\LoginCredentials;
use Carbon\Carbon;

class LoginCredentialsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!is_null(session('login_session_token'))){
            $request->session()->put('login_session_token', $request->session()->get('login_session_token'));
        }
        return $next($request);
    }
}
