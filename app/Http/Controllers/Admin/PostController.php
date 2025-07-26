<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; 
use App\Http\Controllers\Controller;
use App\Services\AdminPostService;
use App\Http\Requests\Admin\StorePostRequest;


class PostController extends Controller
{
    protected $adminPostService;

    public function __construct(AdminPostService $adminPostService)
    {
        $this->adminPostService = $adminPostService;
    }
    public function index(Request $request)
    {       
        if ($request->ajax()) {
            return response()->json($this->adminPostService->getPosts($request));
        }
        return view('admin.posts.index');
    }

    /**
     * Show the form for creating a new resource.
     */
     public function create()
    {
        return view('admin.posts.create');
    }

    // Lưu bài viết mới
    public function store(StorePostRequest $request)
    {
        try {
            $data = $request->validated();
            $thumbnail = $request->hasFile('thumbnail') ? $request->file('thumbnail') : null;
            
            $post = $this->adminPostService->create($data, $thumbnail);

            return to_route('admin.posts.index')->with('success', 'Tạo bài viết thành công');
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
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
        
        try {
            $data = $request->validated();            
            $thumbnail = $request->hasFile('thumbnail') ? $request->file('thumbnail') : null;

            $updatedPost = $this->adminPostService->update($post, $data, $thumbnail);

            return to_route('admin.posts.index')->with('success', 'Cập nhật bài viết thành công');
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }



    // Xem chi tiết bài viết
    public function show(Post $post)
    {
        $this->authorize('view', $post);
        return view('admin.posts.show', compact('post'));
    }

    // Xóa mềm bài viết
   public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $this->adminPostService->delete($post);
        return response()->json(['success' => true]);
    }

    public function destroyAll()
    {
        $this->adminPostService->deleteAll(Auth::id());
        return response()->json(['success' => true]);
    }
}