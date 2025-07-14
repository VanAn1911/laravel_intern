<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostService
{
    public function getPosts($request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);

        $query = Post::where('user_id', Auth::id());

        $totalRecords = $query->count();

        $posts = $query->offset($start)->limit($length)->get();

        $data = [];
        foreach ($posts as $index => $post) {
            $statusEnum = $post->status;

            $statusHtml = '<span class="badge bg-' . $statusEnum->color() . '">' . $statusEnum->label() . '</span>';

            $actionHtml = '<a href="' . route('posts.show', $post) . '" class="btn btn-info btn-sm">Show <i class="fas fa-eye"></i></a>';

            if (Auth::user()->can('update', $post)) {
                $actionHtml .= ' <a href="' . route('posts.edit', $post) . '" class="btn btn-warning btn-sm">Edit <i class="fas fa-edit"></i></a>';
            }

            if (Auth::user()->can('delete', $post)) {
                $actionHtml .= '
                    <form action="' . route('posts.destroy', $post) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Bạn có chắc muốn xóa?\')">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm">Delete <i class="fas fa-trash-alt"></i></button>
                    </form>';
            }
            $data[] = [
                'varIndex' => $start + $index + 1,
                'title' => $post->title,
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
            'recordsFiltered' => $totalRecords,
            'data' => $data,
        ];
    }
}