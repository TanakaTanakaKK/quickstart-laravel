<?php

namespace App\Http\Middleware;

use App\Enums\Prefecture;
use App\Models\LoginCredential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
            cache()->forget('weather_info');
            return to_route('login_credential.create');
        }

        cache()->remember('weather_info', 300, function() use($login_credential){

            $url = config('services.open_weather.url').Prefecture::getKey($login_credential->user->prefecture).'&appid='.config('services.open_weather.key');
            $prefecture_japanese_name = Prefecture::getDescription($login_credential->user->prefecture);

            try{
                $response = Http::timeout(3)->get($url);
                $data = $response->getBody();
                $data = json_decode($data, true);
                return [
                    'prefecture' => $prefecture_japanese_name,
                    'icon_url' => 'https://openweathermap.org/img/wn/'.$data['weather'][0]['icon'].'.png',
                    'current_temperature' => $data['main']['temp'].'℃',
                ];
            }catch(Exception $e){
                return [
                    'prefecture' => '',
                    'icon_url' => '',
                    'current_temperature' => '',
                ];
            }
        });
        
        return $next($request);
    }
}
