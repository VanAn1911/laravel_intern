<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Requests\User\LikeRequest;
use App\Services\NewsService;
use Mews\Purifier\Facades\Purifier;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index()
    {
        $posts = $this->newsService->getApprovedPosts();
        return view('news.index', compact('posts'));
    }

    public function show(Post $post)
    {
        if ($post->status !== \App\Enums\PostStatus::APPROVED) {
            abort(404);
        }

        return view('news.show', compact('post'));
    }

    public function toggleLike(LikeRequest $request)
    {
        $data = $request->validated();

        $result = $this->newsService->toggleLike(
            $data['type'],
            $data['id'],
            $data['is_like']
        );

        return response()->json($result);
    }

    public function comment(Request $request, Post $post)
    {
        $content = strip_tags(Purifier::clean($request->validate(['content' => 'required|string'])['content']));

        $this->newsService->storeComment($post, $content);
        return back();
    }

    public function reply(Request $request, Comment $comment)
    {
        //sử dụng strip_tags để 
        $content = strip_tags(Purifier::clean($request->validate(['content' => 'required|string'])['content']));

        $this->newsService->storeReply($comment, $content);
        return back();
    }
}
