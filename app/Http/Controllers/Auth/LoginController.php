<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
   
    // Sử dụng trait AuthenticatesUsers để xử lý đăng nhập
    // Trait này cung cấp các phương thức như login, logout, authenticated, showLoginForm
    // và các thuộc tính như redirectTo, guard, etc.
    // Bạn có thể tùy chỉnh các phương thức này theo nhu cầu của mình.
    // Trait này cũng cung cấp các phương thức để xử lý đăng xuất người dùng,
    use AuthenticatesUsers;

    protected $redirectTo = '/posts';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
 * @param \App\Http\Requests\LoginRequest $request
 */
    public function login(LoginRequest $request)
    {

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended($this->redirectTo)->with('login_success', true);
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->withInput($request->only('email'));
    }
    // Hiển thị thông báo đăng nhập thành công khi chuyển hướng
    protected function authenticated(Request $request, $user)
    {
        return redirect()->route('posts.index')->with('login_success', true);
    }

    public function showLoginForm(Request $request)
    {
        if (Auth::check()) {
            // Quay lại trang trước đó
            return redirect()->back()->with('info', 'Bạn đã đăng nhập!');
        }
        return view('auth.login');
    }
}
