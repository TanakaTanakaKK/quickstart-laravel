<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetAuthentication extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'user_id',
        'status',
        'expired_at'
    ];

    public function user()
    {
        return $this->belongsTo(PasswordResetAuthentication::class, 'user_id', 'id');
    }
}
