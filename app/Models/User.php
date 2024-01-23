<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'thumbnail_image_path',
        'archive_image_path',
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

    public function resetPasswords(): HasMany
    {
        return $this->hasMany(ResetPassword::class, 'email', 'email');    
    }

    public function loginCredentials(): HasMany
    {
        return $this->hasMany(LoginCredential::class, 'user_id', 'id');
    }

    public function resetEmails() : HasMany
    {
        return $this->hasMany(ResetEmail::class, 'user_id', 'id');
    }
    
    public function tasks()
    {
        return $this->hasMany(Task::class, 'user_id', 'id');
    }

}