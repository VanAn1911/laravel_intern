<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Enums\UserStatus;
use App\Enums\RoleEnum;

class LoginController extends Controller
{
   
    // Sử dụng trait AuthenticatesUsers để xử lý đăng nhập
    // Trait này cung cấp các phương thức như login, logout, authenticated, showLoginForm
    // và các thuộc tính như redirectTo, guard, etc.
    // Bạn có thể tùy chỉnh các phương thức này theo nhu cầu của mình.
    // Trait này cũng cung cấp các phương thức để xử lý đăng xuất người dùng,
    use AuthenticatesUsers;


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
        $credentials['status'] = UserStatus::APPROVED; // Kiểm tra trạng thái người dùng đã được phê duyệt
        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->status !== UserStatus::APPROVED) {
            return back()->withErrors([
                'login' => 'Tài khoản chưa được phê duyệt.',
            ])->withInput($request->only('email'));
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
                if (Auth::user()->role === RoleEnum::ADMIN) {
                    return to_route('admin.dashboard')->with('success', 'Đăng nhập thành công');
                }

                return to_route('posts.index')->with('success', 'Đăng nhập thành công');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->withInput($request->only('email'));
    }
    
    // Hiển thị thông báo đăng nhập thành công khi chuyển hướng
    protected function authenticated(Request $request, $user)
    {   
        return to_route('posts.index')->with('login_success', true);
    }

    public function showLoginForm(Request $request)
    {
        // Kiểm tra nếu người dùng đã đăng nhập
        if (Auth::check()) {
            // Quay lại trang trước đó
            return back()->with('info', 'Bạn đã đăng nhập!');
        }
        return view('auth.login');
    }
}
