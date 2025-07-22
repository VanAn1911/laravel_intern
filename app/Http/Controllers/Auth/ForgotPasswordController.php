<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Jobs\SendResetPasswordEmail;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email không tồn tại trong hệ thống.']);
        }

        // Tạo token reset password
        $token = Str::random(60);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => bcrypt($token),
                'created_at' => now()
            ]
        );

        // Dispatch job gửi mail
        SendResetPasswordEmail::dispatch($user, $token)->onQueue('ResetPasswordEmail');

        return back()->with('status', 'Đã gửi link đặt lại mật khẩu tới email của bạn!');
    }

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }
}