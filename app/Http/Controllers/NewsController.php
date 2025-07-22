<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Enums\PostStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class NewsController extends Controller
{
    // Hiển thị danh sách bài viết đã phê duyệt
    public function index()
    {
        // Lấy bài viết đã phê duyệt, có ngày đăng không null, sắp xếp mới nhất
        $posts =Post::status(PostStatus::APPROVED)//dùng scope status
            ->whereNotNull('publish_date')
            ->orderByDesc('publish_date')
            ->where('publish_date', '<=',now())
            ->paginate(10);

        return view('news.index', compact('posts'));
    }

    // Hiển thị chi tiết bài viết qua slug
    public function show(Post $post)
    {
        if ($post->status !== PostStatus::APPROVED) {
            abort(404);
        }

        return view('news.show', compact('post'));
    }

    // app/Http/Controllers/NewsController.php
    public function like(Post $post)
    {
        $post->likes()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['is_like' => true]
        );
        return back();
    }

    public function dislike(Post $post)
    {
        $post->likes()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['is_like' => false]
        );
        return back();
    }

    public function comment(Request $request, Post $post)
    {
        $request->validate(['content' => 'required|string']);
        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);
        return back();
    }

}