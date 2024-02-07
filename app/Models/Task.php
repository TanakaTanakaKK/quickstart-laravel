<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'expired_at',
        'detail',
        'thumbnail_image_path',
        'archive_image_path',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}