<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class WeatherController extends Controller
{
    public function show(){

        $api_key = 'e28457d1536e8199f3bcdb7710267d73';
        $city_name = 'NIIGATA';
        $url = "http://api.openweathermap.org/data/2.5/weather?units=metric&lang=ja&q=$city_name&appid=$api_key";

        $method = "GET";

        $client = new Client();

        $response = $client->request($method, $url);

        $data = $response->getBody();
        $data = json_decode($data, true);
        $current_weather = $data['weather'][0]['description'];
        $current_temperature = $data['main']['temp'];
        $todays_high_temperature = $data['main']['temp_max'];
        $todays_low_temperature = $data['main']['temp_min'];
        dd($data);
    }
    
    
    
    
}
