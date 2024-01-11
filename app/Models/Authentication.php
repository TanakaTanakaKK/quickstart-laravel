<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Authentication extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'token',
        'email',
        'status',
        'expired_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

}