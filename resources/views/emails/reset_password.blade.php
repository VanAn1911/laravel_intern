<p>Xin chào {{ $user->name ?? $user->email }},</p>
<p>Bạn vừa yêu cầu đặt lại mật khẩu cho tài khoản của mình.</p>
<p>Nhấn vào link dưới đây để đặt lại mật khẩu:</p>
<p>
    <a href="{{ url('password/reset/'.$token.'?email='.$user->email) }}">
        Đặt lại mật khẩu
    </a>
</p>
<p>Nếu bạn không yêu cầu, hãy bỏ qua email này.</p>