<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    // Hiển thị danh sách bài viết
    public function index()
    {
        return response()->json(['message' => 'Danh sách bài viết']);
    }

    // Hiển thị form tạo bài viết mới
    public function create()
    {
        return response()->json(['message' => 'Form tạo bài viết']);
    }

    // Lưu bài viết mới
    public function store(Request $request)
    {
        return response()->json(['message' => 'Lưu bài viết mới']);
    }

    // Hiển thị một bài viết cụ thể
    public function show($id)
    {
        return response()->json(['message' => "Hiển thị bài viết $id"]);
    }

    // Hiển thị form chỉnh sửa bài viết
    public function edit($id)
    {
        return response()->json(['message' => "Form chỉnh sửa bài viết $id"]);
    }

    // Cập nhật bài viết
    public function update(Request $request, $id)
    {
        return response()->json(['message' => "Cập nhật bài viết $id"]);
    }

    // Xóa bài viết
    public function destroy($id)
    {
        return response()->json(['message' => "Xóa bài viết $id"]);
    }
}
