<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class UserController extends Controller
{
    // Hiển thị danh sách người dùng
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('first_name', 'like', "%$keyword%")
                ->orWhere('last_name', 'like', "%$keyword%")
                ->orWhere('email', 'like', "%$keyword%");
            });
        }

        $users = $query->paginate(10);
        return view('admin.users.index', compact('users'));
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

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        DB::beginTransaction();
        try {
            $request->validate([
                'first_name' => 'required|max:50',
                'last_name' => 'required|max:50',
                'address' => 'nullable|max:255',
                'status' => 'required|in:pending,active,banned',
            ]);

            $oldStatus = $user->status;
            $user->update($request->only('first_name', 'last_name', 'address', 'status'));

            if ($oldStatus !== 'banned' && $request->status === 'banned') {
                DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->delete();
            }

            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'Cập nhật user thành công');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }
}
