<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Enums\UserStatus;
use App\Jobs\SendWelcomeEmail;


class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/login';

    // public function __construct()
    // {
    //     $this->middleware('guest');
    // }

    // Sử dụng RegisterRequest để validate
    public function register(RegisterRequest $request)
    {
        $user = $this->create($request->validated());
        SendWelcomeEmail::dispatch($user);
        return to_route('login')->with('success', 'Đăng ký tài khoản thành công!');
    }

    protected function create(array $data)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'password'   => Hash::make($data['password']),
                'status'     => UserStatus::PENDING,
                'role'       => 'user',
            ]);

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack(); // rollback nếu có lỗi
            return back()->withErrors(['register_error' => 'Đăng ký thất bại: ' . $e->getMessage()]);
        }
    }

    public function showRegisterForm(Request $request)
    {
        if (Auth::check()) {
            // Quay lại trang trước đó
            return back()->with('info', 'Bạn đã đăng nhập!');
        }
        return view('auth.register');
    }
}