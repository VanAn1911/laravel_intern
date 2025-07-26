<?php
namespace App\Services;

use App\Models\Post;
use App\Models\Comment;
use App\Enums\PostStatus;
use Illuminate\Support\Facades\Auth;

class NewsService
{
    public function getApprovedPosts()
    {
        return Post::status(PostStatus::APPROVED)
            ->whereNotNull('publish_date')
            ->where('publish_date', '<=', now())
            ->orderByDesc('publish_date')
            ->paginate(10);
    }

    public function toggleLike(string $type, int $id, bool $isLike)
    {
        $class = match (strtolower($type)) {
            'comment' => Comment::class,
            'post' => Post::class,
            default => abort(400, 'Loại không hợp lệ'),
        };

        $model = $class::findOrFail($id);
        $user = Auth::user();

        $like = $model->likes()->where('user_id', $user->id)->first();

        if ($like) {
            if ($like->is_like === $isLike) {
                $like->delete();
                $currentLike = null;
            } else {
                $like->update(['is_like' => $isLike]);
                $currentLike = $isLike ? 1 : 0;
            }
        } else {
            $model->likes()->create([
                'user_id' => $user->id,
                'is_like' => $isLike,
            ]);
            $currentLike = $isLike ? 1 : 0;
        }

        return [
            'like' => $model->likes()->where('is_like', true)->count(),
            'dislike' => $model->likes()->where('is_like', false)->count(),
            'current_like' => $currentLike,
        ];
    }

    public function storeComment(Post $post, string $content)
    {
        return $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $content,
        ]);
    }

    public function storeReply(Comment $comment, string $content)
    {
        $reply = new Comment([
            'user_id' => Auth::id(),
            'content' => $content,
            'parent_id' => $comment->id,
        ]);

        $reply->commentable_id = $comment->commentable_id;
        $reply->commentable_type = $comment->commentable_type;
        $reply->save();

        return $reply;
    }
}
