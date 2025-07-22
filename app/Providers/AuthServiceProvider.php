<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Register model policies here if needed
    ];

    public function boot(): void
    {
        $this->registerPolicies();
        // Gate xác định quyền admin
        Gate::define('is_admin', function (User $user) {
            Log::info('Check is_admin gate:', [
        'role' => $user->role,
        'match' => $user->role === RoleEnum::ADMIN,
    ]);
            return $user->role === RoleEnum::ADMIN;
        });

        Gate::define('is_user', function (User $user) {
            return $user->role === RoleEnum::USER;
        });
    }
}
