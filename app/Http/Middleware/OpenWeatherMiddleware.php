<?php

namespace App\Http\Middleware;

use App\Enums\Prefecture;
use App\Models\LoginCredential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use Closure;

class OpenWeatherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $login_credential = LoginCredential::where('token', session('login_credential_token'))->first();
        if(!is_null(session('login_credential_token')) && !is_null($login_credential)){
            
            $weather_info = Cache::remember('weather_info', 300, function() use($login_credential){

                $api_key = 'e28457d1536e8199f3bcdb7710267d73';
                $prefecture_japanese_name = Prefecture::getDescription($login_credential->users->prefecture);
                $url = 'http://api.openweathermap.org/data/2.5/weather?units=metric&lang=ja&q='.Prefecture::getKey($login_credential->users->prefecture).'&appid='.$api_key;
                $method = 'GET';
        
                $client = new Client();
        
                $response = $client->request($method, $url);
        
                $data = $response->getBody();
                $data = json_decode($data, true);
                return [
                    'prefecture' => $prefecture_japanese_name,
                    'current_weather' => $data['weather'][0]['description'],
                    'current_temperature' => $data['main']['temp'],
                ];
            });
            session()->put('weather_info', $weather_info);   
        }
        return $next($request);
    }
}
