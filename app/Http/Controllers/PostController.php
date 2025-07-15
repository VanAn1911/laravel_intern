<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use App\Services\PostService;


class PostController extends Controller
{
    // Hiển thị danh sách bài viết của user hiện tại
    public function index()
    {
        
        //$posts = Post::where('user_id', Auth::id())->latest()->get(); // KHÔNG paginate()
        return view('posts.index');
    }

    // Hiển thị form tạo bài viết
    public function create()
    {
        return view('posts.create');
    }

    // Lưu bài viết mới
    public function store(StorePostRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated(); //chỉ lấy dữ liệu đã xác thực từ request
            //$data - $request->all(); // Lấy tất cả dữ liệu từ request
            //$data = $request->only('title');
            //$title = $request->input('title'); // Lấy dữ liệu từ trường 'title'
            //$title = $request->title;

            $data['user_id'] = Auth::id();
            $post = Post::create($data);
            // $post = Event::withoutEvents(function () use ($data) {// Tắt sự kiện để tránh observer
            //     return Post::create($data);
            // });

            if ($request->hasFile('thumbnail')) {                
                $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnails');
            }

            DB::commit();
            return to_route('posts.index')->with('success', 'Tạo bài viết thành công');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    // Hiển thị form sửa bài viết
    public function edit(Post $post)
    {
        // $this->authorize('update', arguments: $abc);

        return view('posts.edit', compact('post'));
    }

    // Cập nhật bài viết
    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        DB::beginTransaction();
        try {
            $data = $request->validated();
            
            if (auth()->user()->role === 'admin' && $request->has('status')) {
                $data['status'] = $request->input('status');
            }
            $post->update($data);

            if ($request->hasFile('thumbnail')) {
                $post->clearMediaCollection('thumbnails');
                $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnails');
            }

            DB::commit();
            return to_route('posts.index')->with('success', 'Cập nhật bài viết thành công');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    // Xem chi tiết bài viết
    public function show(Post $post)
    {
        
        $this->authorize('view', $post);
        $post->load('user');// Eager load user relationship
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
        Post::ownedBy(Auth::id())->delete(); // không dùng where, dùng scope
        return to_route('posts.index')->with('success', 'Đã xóa tất cả bài viết');
    }

    public function data(Request $request)
    {
        return response()->json(app(PostService::class)->getPosts($request));
    }
}
