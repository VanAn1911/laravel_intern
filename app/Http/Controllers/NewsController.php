<?php
namespace App\Http\Controllers;

use App\Models\Post;

class NewsController extends Controller
{
    // Hiển thị danh sách bài viết đã phê duyệt
    public function index()
    {
        $posts = Post::where('status', 1)
            ->latest('publish_date')
            ->paginate(10);

        return view('news.index', compact('posts'));
    }

    // Hiển thị chi tiết bài viết qua slug
    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        return view('news.show', compact('post'));
    }
}