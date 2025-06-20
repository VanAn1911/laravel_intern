<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @extends('layouts.master')

    @section('title', 'Ví dụ')

    @section('content')
        <div>
            <!-- Hiển thị biến đơn giản -->
            <p>Tên: {{ $name }}</p>

            <!-- Hiển thị biến với giá trị mặc định nếu không tồn tại -->
            <p>Tuổi: {{ $age ?? 'Không xác định' }}</p>

            <!-- Thoát HTML để tránh XSS -->
            <p>Mô tả: {!! $description !!}</p>
        </div>
        <div>
            <!-- Câu lệnh if-else -->
            @if($user->isAdmin())
                <p>Chào mừng quản trị viên!</p>
            @elseif($user->isMember())
                <p>Chào mừng thành viên!</p>
            @else
                <p>Chào khách!</p>
            @endif

            @foreach ($users as $user)
                <p>{{ $user->name }}</p>
            @endforeach

            @for ($i = 0; $i < 5; $i++)
                <p>{{ $i }}</p>
            @endfor

        </div>
        <x-alert type="danger" message="Có lỗi xảy ra!" />
    @endsection

    @push('styles')
        <link rel="stylesheet" href="style.css">
    @endpush

    @include('layouts.footer')

</body>
</html>