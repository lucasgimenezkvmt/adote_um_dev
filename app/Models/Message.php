<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelogsTo;

class Message extends Model
{
    use HasFactory;

    public function fromUser(): BelongsTo {
        return $this->belongsTo(User::class, 'from_user_to', 'id');
    }

    public function toUser(): BelongsTo {
        return $this->belongsTo(User::class, 'from_user_to', 'id');
    }
}
