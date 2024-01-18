<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class User extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'email',
        'password',
        'name',
        'gender',
        'birthday',
        'phone_number',
        'img_path',
        'kana_name',
        'nickname',
        'postal_code',
        'prefecture',
        'address',
        'block',
        'building'
    ];
    
    public function authentication()
    {
        return $this->hasOne(Authentication::class, 'email', 'email');
    }

}