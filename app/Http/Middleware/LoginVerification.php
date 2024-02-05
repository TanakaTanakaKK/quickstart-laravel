<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use App\Enums\Prefecture;
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

        if(!auth()->check()){
            session()->flush();
            return to_route('login_credential.create');
        }
        
        if(!is_null($request->task) && $request->task->user_id !== auth()->id() && !Gate::allows('isAdmin')){
            return to_route('task.index')->withErrors(['access_error' => '不正なアクセスです。']);
        }
        
        return $next($request);
    }    
}
