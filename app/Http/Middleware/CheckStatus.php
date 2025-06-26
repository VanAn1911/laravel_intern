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
            if ($user->status === UserStatus::PENDING) {
                Auth::logout();
                return redirect()->back()->withErrors(['email' => 'Tài khoản của bạn đang chờ phê duyệt.']);
            }
            if ($user->status === UserStatus::REJECTED) {
                Auth::logout();
                return redirect()->back()->withErrors(['email' => 'Tài khoản của bạn đã bị từ chối.']);
            }
            if ($user->status === UserStatus::BLOCKED) {
                Auth::logout();
                return redirect()->back()->withErrors(['email' => 'Tài khoản của bạn đã bị khóa.']);
            }
        }
        return $next($request);
    }
}