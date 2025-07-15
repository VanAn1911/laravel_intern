<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; 
use App\Http\Controllers\Controller;
use App\Services\AdminPostService;


class AdminPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {       
        return view('admin.posts.index');
    }

    /**
     * Show the form for creating a new resource.
     */
     public function create()
    {
        //
    }

    // Lưu bài viết mới
    public function store(StorePostRequest $request)
    {
        //
    }

    // Hiển thị form sửa bài viết
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('admin.posts.edit', compact('post'));
    }

    // Cập nhật bài viết
    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $data = $request->validated();
        $data['status'] = $request->input('status');
    
        $post->update($data);

        // Xử lý upload thumbnail mới (nếu có)
        if ($request->hasFile('thumbnail')) {
            $post->clearMediaCollection('thumbnails');
            $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnails');
        }

        return to_route('admin.posts.index')->with('success', 'Cập nhật bài viết thành công');
    }

    // Xem chi tiết bài viết
    public function show(Post $post)
    {
        $this->authorize('view', $post);
        return view('posts.show', compact('post'));
    }

    // Xóa mềm bài viết
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return to_route('admin.posts.index')->with('success', 'Xóa bài viết thành công');
    }

    // Xóa tất cả bài viết của user hiện tại
    public function destroyAll()
    {
        Post::where('user_id', Auth::id())->delete();
        return to_route('admin.posts.index')->with('success', 'Đã xóa tất cả bài viết');
    }

    public function data(Request $request)
    {
        return response()->json(app(AdminPostService::class)->getPosts($request));
    }
}