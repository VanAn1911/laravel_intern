<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PostResource;

class PostService
{

    public function getPosts($filters)
    {
        $perPage = $filters['length'] ?? 10;
        $page = isset($filters['start']) ? floor($filters['start'] / $perPage) + 1 : 1;
        $search = $filters['search']['value'] ?? null;
        $orderColumnIndex = $filters['order'][0]['column'] ?? 0;
        $orderDir = $filters['order'][0]['dir'] ?? 'asc';
        $orderColumn = $filters['columns'][$orderColumnIndex]['data'] ?? 'created_at';

        $query = Post::ownedBy(Auth::id());

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('status', 'like', "%$search%");
            });
        }

        $columnMapping = [
            'varIndex' => 'id',
            'title' => 'title',
            'thumbnail' => 'id',
            'description' => 'description',
            'publish_date' => 'publish_date',
            'status_label' => 'status',
            'action' => 'id'
        ];
        $dbColumn = $columnMapping[$orderColumn] ?? 'created_at';
        $validOrderColumns = ['id', 'title', 'description', 'publish_date', 'status', 'created_at'];
        if (in_array($dbColumn, $validOrderColumns)) {
            $query->orderBy($dbColumn, $orderDir);
        } else {
            $query->latest();
        }
        //Sử dụng pagination để phân trang dữ liệu tự động
        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        $items = $paginator->items();
        foreach ($items as $i => $item) {
            $item->resourceIndex = $paginator->firstItem() + $i; // truyền index vào resource
        }

        return [
            'draw' => (int) ($filters['draw'] ?? 1),
            'recordsTotal' => $paginator->total(),
            'recordsFiltered' => $paginator->total(),
            'data' => PostResource::collection(collect($items)),
        ];

    }


    public function create(array $data, $thumbnail = null)
    {
        DB::beginTransaction();
        try {
            $data['user_id'] = Auth::id();
            $post = Post::create($data);

            if ($thumbnail) {
                $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnails');
            }

            DB::commit();
            return $post;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Post $post, array $data, $thumbnail = null)
    {
        DB::beginTransaction();
        try {            
            $post->update($data);

            if ($thumbnail) {
                $post->clearMediaCollection('thumbnails');
                $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnails');
            }

            DB::commit();
            return $post->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(Post $post)
    {
        return $post->delete();
    }

    public function deleteAll($userId)
    {
        return Post::ownedBy($userId)->delete();
    }
}