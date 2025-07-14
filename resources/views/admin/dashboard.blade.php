@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Trang quản trị</h1>
    <ul>
        <li><a href="{{ route('posts.index') }}">Quản lý bài viết</a></li>
        <li><a href="{{ route('users.index') }}">Quản lý tài khoản</a></li>
    </ul>
</div>
@endsection
