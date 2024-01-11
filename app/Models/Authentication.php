<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Authentication extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'token',
        'email',
        'status',
        'expired_at'
    ];
}