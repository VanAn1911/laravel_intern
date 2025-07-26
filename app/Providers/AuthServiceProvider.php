<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use App\Policies\PostPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        //Post::class => PostPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
        //Các cách đăng ký policy
        // Gate::policy(Post::class, PostPolicy::class);
        // Gate::define('view-post', function (User $user, Post $post) {
        //     return $user->id === $post->user_id || $user->role === RoleEnum::ADMIN;
        // });
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
