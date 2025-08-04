<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendDynamicMailJob;
use App\Enums\PostStatus;
use App\Jobs\SendStatusUpdatedMail;
use Illuminate\Database\Eloquent\Builder;

class AdminPostService
{
    

    public function getPosts($filters)
    {
        $perPage = $filters['length'] ?? 10;
        $page = isset($filters['start']) ? floor($filters['start'] / $perPage) + 1 : 1;
        $orderColumnIndex = $filters['order'][0]['column'] ?? 0;
        $orderDir = $filters['order'][0]['dir'] ?? 'asc';
        $orderColumn = $filters['columns'][$orderColumnIndex]['data'] ?? 'created_at';

        $query = Post::query()->with('user');

        // Lọc nhiều trường
        $title = $filters['title'] ?? null;
        $email = $filters['email'] ?? null;
        $status = $filters['status'] ?? null;

        $query->when($title, fn($q) => 
            $q->whereAny(['title', 'description'], 'like', "%$title%")
        )
            ->when($email, fn($q) => $q->whereHas('user', fn($q2) => $q2->where('email', 'like', "%$email%")))
            ->when($status !== null && $status !== '', fn($q) => $q->where('status', $status));


        // Sắp xếp
        $columnMapping = [
            'title' => 'title',
            'user.email' => 'email',
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

    public function create(array $data, $thumbnail = null)
    {
        DB::beginTransaction();
        try {
            $data['user_id'] = Auth::id();
            $data['status'] = PostStatus::APPROVED;
            $post = Post::create($data);

            if ($thumbnail) {
                Log::info('Thumbnail received', ['file' => $thumbnail]);
                $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnails');
                Log::info('Media added to collection', ['post_id' => $post->id]);
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
            $oldStatus = $post->status;            
            $post->update($data);

            if ($thumbnail) {
                $post->clearMediaCollection('thumbnails');
                $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnails');
            }

            DB::commit();

            if ($oldStatus !== $post->status) {
                $post = $post->fresh(['user']);//reload user relationship
                SendStatusUpdatedMail::dispatch($post);
                //->onQueue('SendStatusUpdatedMail')
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
