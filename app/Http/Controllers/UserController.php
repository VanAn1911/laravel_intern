<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Hiển thị danh sách người dùng
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Hiển thị thông tin một người dùng cụ thể
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }
}
