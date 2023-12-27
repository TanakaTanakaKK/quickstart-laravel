<?php
namespace App\Libraries;

use Illuminate\Support\Facades\Facade;

class CommonFunctions extends Facade
{
    public function is_foo($data) 
    { 
        return ($data == 'foo') ? true : false ; 
    } 
}
