<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function user()
    {
        // Mối quan hệ giữa Post và User
        return $this->belongsTo(User::class);
    }
}