<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Str;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }

    protected function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;

        $exists = Post::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();

        if ($exists) {
            if ($ignoreId) {
                // Update: hash theo id
                $hash = substr(md5($ignoreId), 0, 6);
            } else {
                // Store: hash uuid
                $hash = substr(md5(Str::uuid()), 0, 6);
            }
            $slug = $baseSlug . '-' . $hash;
        }

        return $slug;
    }

    public function creating(Post $post)
    {
        $post->slug = $this->generateUniqueSlug($post->title);
    }

    public function updating(Post $post)
    {
        if ($post->isDirty('title')) {
            $post->slug = $this->generateUniqueSlug($post->title, $post->id);
        }
    }
    
}
