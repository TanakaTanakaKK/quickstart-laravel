<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
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

        if(is_null(session('login_credential_token')) || is_null($login_credential)){
            return to_route('login_credential.create');
        }
        if(is_null($request->session()->get('user_id'))){
            $request->session()->put(['user_id' => $login_credential->user_id]);
        }

        $request->session()->put('login_credential_token', $request->session()->get('login_credential_token'));

        if($login_credential->user->role === UserRole::ADMIN){
            $request->session()->put(['user_role' => UserRole::ADMIN]);
        }
        
        if(!is_null($request->task) && $request->task->user_id !== $login_credential->user_id && $login_credential->user->role !== UserRole::ADMIN){
            return to_route('task.index')->withErrors(['access_error' => '不正なアクセスです。']);
        }
        
        return $next($request);
    }    
    
}
