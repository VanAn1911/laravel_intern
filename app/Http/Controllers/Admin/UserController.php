<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Services\AdminUserService;
use App\Http\Requests\AdminUpdateProfileRequest;

class UserController extends Controller
{
    protected $userService;
    public function __construct(AdminUserService $userService)
    {
        $this->userService = $userService;
    }

    // DataTables AJAX
    public function data(Request $request)
    {
        return response()->json($this->userService->getUsers($request->all()));
    }

    // Hiển thị danh sách người dùng
    public function index()
    {
        return view('admin.users.index');
    }

    public function show($id)
    {
        //
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(AdminUpdateProfileRequest $request, User $user)
    {
        $data = $request->validated();
        $user = $this->userService->updateUser($user, $data);

        return to_route('admin.users.index')->with('success', 'Cập nhật user thành công');
    }


    public function lock(User $user)
    {
        $user = $this->userService->lockUser($user);
        return response()->json(['message' => 'Đã khóa người dùng thành công']);
    }
}
