<?php

namespace App\Services;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Builder;
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

        // Lọc nhiều trường
        $name = $filters['name'] ?? null;
        $email = $filters['email'] ?? null;
        $status = $filters['status'] ?? null;

        $query->when($name, fn(Builder $q) =>
            $q->whereAny(['first_name', 'last_name'], 'like', "%$name%")
              ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$name%"])
        )
        ->when($email, fn(Builder $q) => $q->where('email', 'like', "%$email%"))
        ->when($status !== null && $status !== '', fn(Builder $q) => $q->where('status', $status));


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