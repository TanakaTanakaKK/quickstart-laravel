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
        if(!is_null(session('login_credential_token')) && LoginCredential::where('token', session('login_credential_token'))->exists()){
                
            $weather_info = Cache::remember('weather_info', 60, function(){
                $api_key = 'e28457d1536e8199f3bcdb7710267d73';
                $city_name = 'NIIGATA';
                $url = "http://api.openweathermap.org/data/2.5/weather?units=metric&lang=ja&q=$city_name&appid=$api_key";
        
                $method = "GET";
        
                $client = new Client();
        
                $response = $client->request($method, $url);
        
                $data = $response->getBody();
                $data = json_decode($data, true);
                return [
                    'current_weather' => $data['weather'][0]['description'],
                    'current_temperature' => $data['main']['temp'],
                    'todays_high_temperature' => $data['main']['temp_max'],
                    'todays_low_temperature' => $data['main']['temp_min']
                ];
            });

            dd($weather_info);
        }else{
            return $next($request);
        }

        
    }
}
