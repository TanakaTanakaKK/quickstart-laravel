<?php

namespace App\Http\Middleware;

use App\Models\LoginSession;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

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
            $login_session = LoginSession::where('token', session('login_session_token'))->first();

            if(is_null($login_session)){
                $request->session()->forget('login_session_token');
            }else{
                $login_session->logged_in_at = now();
                $login_session->save();
                $request->session()->put('login_session_token', $login_session->token);
            }        
        }    
        return $next($request);
    }    
    
}
