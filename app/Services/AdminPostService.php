<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendDynamicMailJob;

class AdminPostService
{
    

    public function getPosts($filters)
    {
        $perPage = $filters['length'] ?? 10;
        $page = isset($filters['start']) ? floor($filters['start'] / $perPage) + 1 : 1;
        $search = $filters['search']['value'] ?? null;
        $orderColumnIndex = $filters['order'][0]['column'] ?? 0;
        $orderDir = $filters['order'][0]['dir'] ?? 'asc';
        $orderColumn = $filters['columns'][$orderColumnIndex]['data'] ?? 'created_at';

        $query = Post::query();       
        $query->with('user');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('email', 'like', "%$search%");
                  });
            });
        }

        // Map frontend column names to DB columns
        $columnMapping = [
            'title' => 'title',
            'user' => 'user',
            'thumbnail' => 'id',
            'description' => 'description',
            'publish_date' => 'publish_date',
            'status' => 'status',
            'created_at' => 'created_at',
        ];
         $orderBy = $columnMapping[$orderColumn] ?? 'created_at';
         $validOrderColumns = ['title', 'publish_date', 'description', 'created_at', 'email'];
        if (in_array($orderBy, $validOrderColumns)) {
            if ($orderBy === 'email') {
                $query->join('users', 'posts.user_id', '=', 'users.id')
                      ->orderBy('users.email', $orderDir)
                      ->select('posts.*');
            } else {
                $query->orderBy($orderBy, $orderDir);
            }
        } else {
            $query->latest();
        }

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        $items = $paginator->items();

        return [
            'draw' => (int) ($filters['draw'] ?? 1),
            'recordsTotal' => $paginator->total(),
            'recordsFiltered' => $paginator->total(),
            'data' => PostResource::collection(collect($items)),
        ];

    }

    public function update(Post $post, array $data, $thumbnail = null)
    {
        DB::beginTransaction();
        try {
            $oldStatus = $post->status;            
            $post->update($data);

            if ($thumbnail) {
                $post->clearMediaCollection('thumbnails');
                $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnails');
            }

            DB::commit();

            if ($oldStatus !== $post->status) {
                $post = $post->fresh(['user']);//reload user relationship
                SendDynamicMailJob::dispatch('status', ['post' => $post]);
            }
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
