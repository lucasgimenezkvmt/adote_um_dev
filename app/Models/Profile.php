<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelogsTo;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider',
        'provider_user_id',
        'nickname',
        'avatar',
        'data'
    ];

      public function profile(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
