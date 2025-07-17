<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PostService;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        return view('posts.index');
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(StorePostRequest $request)
    {
        try {
            $data = $request->validated();
            $thumbnail = $request->hasFile('thumbnail') ? $request->file('thumbnail') : null;
            
            $post = $this->postService->create($data, $thumbnail);
            
            return to_route('posts.index')->with('success', 'Tạo bài viết thành công');
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);
        $post->load('user');
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        
        try {
            $data = $request->validated();            
            $thumbnail = $request->hasFile('thumbnail') ? $request->file('thumbnail') : null;
            
            $updatedPost = $this->postService->update($post, $data, $thumbnail);
            
            return to_route('posts.index')->with('success', 'Cập nhật bài viết thành công');
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $this->postService->delete($post);
        return response()->json(['success' => true]);
    }

    public function destroyAll()
    {
        $this->postService->deleteAll(Auth::id());
        return response()->json(['success' => true]);
    }

    public function data(Request $request)
    {
        return response()->json($this->postService->getPosts($request->all()));
    }
}