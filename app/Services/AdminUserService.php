<?php

namespace App\Services;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Enums\UserStatus;
class AdminUserService
{
    public function getUsers($filters)
    {
        $perPage = $filters['length'] ?? 10;
        $page = isset($filters['start']) ? floor($filters['start'] / $perPage) + 1 : 1;
        $search = $filters['search']['value'] ?? null;
        $orderColumnIndex = $filters['order'][0]['column'] ?? 0;
        $orderDir = $filters['order'][0]['dir'] ?? 'asc';
        $orderColumn = $filters['columns'][$orderColumnIndex]['data'] ?? 'created_at';

        $query = User::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('address', 'like', "%$search%");
            });
        }

        $columnMapping = [
            'name' => 'first_name',
            'email' => 'email',
            'address' => 'address',
            'status' => 'status',
            'created_at' => 'created_at',
        ];
        $orderBy = $columnMapping[$orderColumn] ?? 'created_at';
        $validOrderColumns = ['first_name', 'email', 'address'];
        if (in_array($orderBy, $validOrderColumns)) {
            $query->orderBy($orderBy, $orderDir);
        } else {
            $query->latest();
        }

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);//truyền length thôi

        $items = $paginator->items();

        return [
            'draw' => (int) ($filters['draw'] ?? 1),
            'recordsTotal' => $paginator->total(),
            'recordsFiltered' => $paginator->total(),
            'data' => UserResource::collection(collect($items)),
        ];
    }

    public function updateUser(User $user, $data)
    {
        DB::beginTransaction();

        try {
            Log::info('Update data:', $data);
            $oldStatus = $user->status;
            $user->update($data);

            if (
                $oldStatus !== 'BLOCKED'
                && isset($data['status'])
                && $data['status'] === 'BLOCKED'
            ) {
                DB::table('sessions')->where('user_id', $user->id)->delete();
            }

            DB::commit();
            return $user;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update user failed: ' . $e->getMessage(), [
                'userId' => $user->id,
                'exception' => $e,
            ]);
            throw $e;
        }
    }

    public function lockUser(User $user)
    {
        $user->update(['status' => UserStatus::BLOCKED]);
        DB::table('sessions')->where('user_id', $user->id)->delete();
        $user->refresh();
        return $user;
    }
}