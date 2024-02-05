<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
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
        'building',
        'role'
    ];
    
    public function authentication()
    {
        return $this->hasOne(Authentication::class, 'email', 'email');
    }

    public function loginCredentials(): HasMany
    {
        return $this->hasMany(LoginCredential::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function taskComments()
    {
        return $this->hasMany(TaskComment::class, 'user_id', 'id');
    }
}