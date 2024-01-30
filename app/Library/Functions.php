<?php

namespace App\Library;

use Illuminate\Support\Facades\Facade;

class Functions extends Facade
{
    public static function get_request_ip() {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipArray = array_map('trim', explode(',', $_SERVER["HTTP_X_FORWARDED_FOR"]));
            $ip = $ipArray[0];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}