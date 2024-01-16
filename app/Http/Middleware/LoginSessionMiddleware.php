<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
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
            $request->session()->regenerate();
            LoginSession::where('token', session('login_session_token'))
                ->update(['updated_at' => Carbon::now()]);
            }
        
        return $next($request);
    }
}
