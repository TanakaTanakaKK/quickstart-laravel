<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'gender',
        'birthday',
        'phone_number',
        'img_path',
        'kana_name',
        'nickname',
        'postal_code',
        'prefecture',
        'city',
        'block',
        'building'
    ];
}
