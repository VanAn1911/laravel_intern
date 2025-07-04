<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class PostController extends Controller
{
    // Hiển thị danh sách bài viết của user hiện tại
    public function index()
    {
        //sử dụng pagination để phân trang bài viết
        $posts = Post::where('user_id', Auth::id())->latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    // Hiển thị form tạo bài viết
    public function create()
    {
        return view('posts.create');
    }

    // Lưu bài viết mới
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['slug'] = Str::slug($data['title']); //tự động tạo slug từ tiêu đề
        $post = Post::create($data);
        // Xử lý upload thumbnail
        if ($request->hasFile('thumbnail')) {
            $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnails');
        }

        return redirect()->route('posts.index')->with('success', 'Tạo bài viết thành công');
    }

    // Hiển thị form sửa bài viết
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    // Cập nhật bài viết
    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $data = $request->validated();

        // Nếu title thay đổi thì cập nhật slug
        if ($data['title'] !== $post->title) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['title']);
        }

        // Chỉ admin mới được đổi status
        if (auth()->user()->role === 'admin' && $request->has('status')) {
            $data['status'] = $request->input('status');
        }

        $post->update($data);

        // Xử lý upload thumbnail mới (nếu có)
        if ($request->hasFile('thumbnail')) {
            $post->clearMediaCollection('thumbnails');
            $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnails');
        }

        return to_route('posts.index')->with('success', 'Cập nhật bài viết thành công');
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
        return to_route('posts.index')->with('success', 'Xóa bài viết thành công');
    }

    // Xóa tất cả bài viết của user hiện tại
    public function destroyAll()
    {
        Post::where('user_id', Auth::id())->delete();
        return to_route('posts.index')->with('success', 'Đã xóa tất cả bài viết');
    }
}
