<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckStatus
{
    public function handle(Request $request, Closure $next)
    {
        // //Check Auth
        // if (Auth::check() && Auth::user()->status == "0") {
        //     return redirect()->route('login')->withErrors(['email' => 'Tài khoản của bạn đang chờ phê duyệt.']);
        // }
        $user = Auth::user();
        if ($user) {
            if ($user->status == 0) {
                Auth::logout();
                return redirect()->back()->withErrors(['email' => 'Tài khoản của bạn đang chờ phê duyệt.']);
            }
            if ($user->status == 2) {
                Auth::logout();
                return redirect()->back()->withErrors(['email' => 'Tài khoản của bạn đã bị từ chối.']);
            }
            if ($user->status == 3) {
                Auth::logout();
                return redirect()->back()->withErrors(['email' => 'Tài khoản của bạn đã bị khóa.']);
            }
        }
        return $next($request);
    }
}