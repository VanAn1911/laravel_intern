<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminPostService
{
    public function getPosts($request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $query = Post::query();

        // Tìm kiếm theo title hoặc email user
        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                ->orWhereHas('user', function ($q2) use ($search) {
                    $q2->where('email', 'like', "%$search%");
                });
            });
        }

        $totalRecords = Post::count();
        $recordsFiltered = $query->count();

        // Sắp xếp (ordering)
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $orderColumn = $request->input("columns.$orderColumnIndex.data");
        $validOrderColumns = ['title', 'publish_date', 'email', 'description'];
        if (in_array($orderColumn, $validOrderColumns)) {
            if ($orderColumn == 'email') {
            $query->join('users', 'posts.user_id', '=', 'users.id')
                  ->orderBy('users.email', $orderDir)
                  ->select('posts.*');
            } else {
                $query->orderBy($orderColumn, $orderDir);
            }
        } else {
            $query->latest();
        }

        // Lấy dữ liệu theo trang
        $posts = $query->offset($start)->limit($length)->get();

        // Chuẩn bị data gửi về DataTables
        $data = [];        
        foreach ($posts as $index => $post) {
            $statusEnum = $post->status;
            $statusHtml = '<span class="badge bg-' . $statusEnum->color() . '">' . $statusEnum->label() . '</span>';
            $actionHtml = '<a href="' . route('posts.show', $post) . '" class="btn btn-info btn-sm">Show <i class="fas fa-eye"></i></a>';
            if (Auth::user()->can('update', $post)) {
                $actionHtml .= ' <a href="' . route('admin.posts.edit', $post) . '" class="btn btn-warning btn-sm">Edit <i class="fas fa-edit"></i></a>';
            }

            if (Auth::user()->can('delete', $post)) {
                $actionHtml .= '
                    <form action="' . route('admin.posts.destroy', $post) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Bạn có chắc muốn xóa?\')">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm">Delete <i class="fas fa-trash-alt"></i></button>
                    </form>';
            }

            $data[] = [
                'varIndex' => $start + $index + 1,
                'title' => $post->title,
                'email' => $post->user->email,
                'thumbnail' => $post->thumbnail,
                'description' => $post->description,
                'publish_date' => $post->publish_date ? $post->publish_date->format('d/m/Y') : '',
                'status_label' => $statusHtml,
                'action' => $actionHtml,
            ];
        }

        return [
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];
    }
}
