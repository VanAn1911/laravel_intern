<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserStatus;

class CheckStatus
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user) {
            switch ($user->status) {
                case UserStatus::APPROVED:
                    break;
                case UserStatus::PENDING:
                    Auth::logout();
                    return back()->withErrors(['login' => 'Tài khoản của bạn đang chờ phê duyệt.']);
                case UserStatus::REJECTED:
                    Auth::logout();
                    return back()->withErrors(['login' => 'Tài khoản của bạn đã bị từ chối.']);
                case UserStatus::BLOCKED:
                    Auth::logout();
                    return back()->withErrors(['login' => 'Tài khoản của bạn đã bị khóa.']);
                default:
                    Auth::logout();
                    return back()->withErrors(['login' => 'Tài khoản của bạn không hợp lệ.']);
            }
        }
        return $next($request);
        }
}