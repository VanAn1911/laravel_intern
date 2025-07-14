<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use App\Models\User;
use App\Enums\PostStatus;
use App\Observers\PostObserver;


class Post extends Model implements HasMedia

{
    use HasFactory, SoftDeletes, InteractsWithMedia;
    protected $casts = [
        'publish_date' => 'datetime',
        'status' => PostStatus::class,
    ];

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'content',
        'publish_date',
        'status',
    ];
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

     public function user()
    {
        // Mối quan hệ giữa Post và User
        return $this->belongsTo(User::class);
    }

    // Accessor cho thumbnail
    public function getThumbnailAttribute()
    {
        return $this->getFirstMediaUrl('thumbnails') ?: asset('storage/default-thumbnail.jpg');
    }
    protected static function booted()
    {
        static::observe(PostObserver::class);
    }

}