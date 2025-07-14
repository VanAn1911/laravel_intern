<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Enums\PostStatus;


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
}