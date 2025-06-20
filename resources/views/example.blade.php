   @extends('layouts.master')

    @section('title', 'Ví dụ')

    @section('content')
        <div>
            <!-- Hiển thị biến đơn giản -->
            <p>Tên: {{ $name }}</p>
        </div>
        <div>
                {-- If-Else --}}
        @if($name == 'An')
            <p>Xin chào, {{ $name }}!</p>
        @else
            <p>Xin chào, khách!</p>
        @endif

        {{-- Foreach --}}
        <ul>
            @foreach($users as $user)
                <li>{{ $user->name }}</li>
            @endforeach
        </ul>

        {{-- For loop --}}
        <ul>
            @for($i = 1; $i <= 5; $i++)
                <li>Item số {{ $i }}</li>
            @endfor
        </ul>
            

        </div>
        <x-alert type="danger" message="Có lỗi xảy ra!" />
    @endsection

    @push('styles')
        <link rel="stylesheet" href="style.css">
    @endpush
