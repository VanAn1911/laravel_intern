   @extends('layouts.master')

    @section('title', 'Ví dụ')

    @section('content')
        <div>
            <!-- Hiển thị biến đơn giản -->
            <p>Tên: {{ $name }}</p>
        </div>
        <div>
        {{-- {-- If-Else --}}
        @if($name == 'An')
            <p>Xin chào, {{ $name }}!</p>
        @else
            <p>Xin chào, khách!</p>
        @endif

        {{-- Foreach --}}
        <p>Danh sách người dùng:</p>
        <ul>
            @foreach($users as $user)
                <li>{{ $loop->first }}-{{ $user->name }}</li>
            @endforeach
        </ul>

        {{-- For loop --}}
        <p>Danh sách số từ 1 đến 5:</p>
        <ul>
            @for($i = 1; $i <= 5; $i++)
                <li>Item số {{ $i }}</li>
            @endfor
        </ul>
            

        </div>
        <p>Hiển thị bằng x-component</p>
        <x-alert type="danger" message="Lỗi đăng nhập">
            Hãy kiểm tra lại tên tài khoản và mật khẩu.
        </x-alert>
        <br>
        <p>Hiển thị bằng @ component</p>
        @component('components.alert', ['type' => 'danger', 'message' => 'Lỗi dữ liệu'])
            Lỗi rồi! Dữ liệu không tồn tại.
        @endcomponent


    @endsection

    @push('styles')
        <link rel="stylesheet" href="style.css">
    @endpush
