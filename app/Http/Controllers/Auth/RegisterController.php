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
        return redirect('/login')->with('success', 'Đăng ký tài khoản thành công!');
    }

    protected function create(array $data)
    {
        try {
            // Sử dụng transaction để đảm bảo tính toàn vẹn dữ liệu, nếu có lỗi xảy ra, sẽ rollback toàn bộ giao dịch
            return DB::transaction(function () use ($data) {
                $user = User::create([
                    'first_name' => $data['first_name'],
                    'last_name'  => $data['last_name'],
                    'email'      => $data['email'],
                    'password'   => Hash::make($data['password']),
                    'status'     => UserStatus::Pending,
                    'role'       => 'user',
                ]);


                return $user;
            });
        } catch (\Exception $e) {
            // Xử lý lỗi nếu cần
            return back()->withErrors(['register_error' => 'Đăng ký thất bại: ' . $e->getMessage()]);
        }
    }

    public function showRegisterForm(Request $request)
    {
        if (Auth::check()) {
            // Quay lại trang trước đó
            return redirect()->back()->with('info', 'Bạn đã đăng nhập!');
        }
        return view('auth.register');
    }
}