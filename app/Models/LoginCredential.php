<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginCredential extends Model
{
    use HasFactory;

    protected $fillable = [
        'logged_in_at',
        'user_id',
        'token'
    ];
}
