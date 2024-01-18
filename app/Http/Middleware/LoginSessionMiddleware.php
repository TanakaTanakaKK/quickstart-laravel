<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginSession;
use Carbon\Carbon;

class LoginSessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!is_null(session('login_session_token'))){
            if(is_null(LoginSession::where('token', session('login_session_token'))->first())){
                $request->session()->forget('login_session_token');
            }
            $csrf_token = $request->session()->get('_token');
            $request->session()->regenerate();
            $request->session()->put('_token', $csrf_token);
        }
        
        return $next($request);
    }
}
