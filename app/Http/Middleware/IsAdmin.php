<?php
namespace App\Http\Middleware;
use App\Enums\RoleEnum;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === RoleEnum::ADMIN) {
            return $next($request);
        }

        abort(404);
    }
}