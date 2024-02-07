<?php

namespace App\Http\Middleware;

use App\Enums\Prefecture;
use App\Models\LoginCredential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Cache,
    Http,
};
use Symfony\Component\HttpFoundation\Response;
use Closure;
use Exception;

class OpenWeather
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
            Cache::forget('weather_info'.$login_credential->user->prefecture);
            return to_route('login_credential.create');
        }
        
        session()->put('prefecture_number',$login_credential->user->prefecture);

        Cache::remember('weather_info'.$login_credential->user->prefecture, 10800, function() use($login_credential){
            $url = config('services.open_weather.url').Prefecture::getKey($login_credential->user->prefecture).'&appid='.config('services.open_weather.key');
            $prefecture_description = Prefecture::getDescription($login_credential->user->prefecture);

            $result = [
                'prefecture' => '',
                'icon_url' => '',
                'temperature' => '',
            ];

            try{
                $response = Http::timeout(3)->get($url);
                $api_result = json_decode($response->getBody(), true);
                
                $result['prefecture'] = $prefecture_description;
                $result['icon_url'] = 'https://openweathermap.org/img/wn/'.$api_result['weather'][0]['icon'].'.png';
                $result['temperature'] = $api_result['main']['temp'].'℃';
                
            }catch(Exception $e){
            }
            
            return $result;
        });
        
        return $next($request);
    }
}
