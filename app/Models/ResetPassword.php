<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResetPassword extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'email',
        'status',
        'expired_at'
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class,'email','email');
    }
}
