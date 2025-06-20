<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
   

    use AuthenticatesUsers;

    protected $redirectTo = '/posts';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    // Hiển thị thông báo đăng nhập thành công khi chuyển hướng
    protected function authenticated(Request $request, $user)
    {
        return redirect()->route('posts.index')->with('login_success', true);
    }
}
