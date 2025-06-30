   @extends('layouts.master')

    @section('title', 'Ví dụ')

    @section('content')
         <form method="GET" action="{{ route('demo') }}">
            <div class="mb-3">
                <label for="title" class="form-label">Nhập tiêu đề:</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ request('title') }}">
            </div>
            <button type="submit" class="btn btn-primary">Gửi</button>
        </form>

    {{-- <p>Tiêu đề từ input: {!! request('title') !!}</p> <!-- LỖ HỔNG XSS -->  --}}
    {{-- {{-- <p>Tiêu đề từ input: {{ request('title') }}</p>  --}}
    <p>Tiêu đề đã được làm sạch: {!! $safeTitle !!}</p> --}}

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
