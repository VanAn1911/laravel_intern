@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center mt-5">
    <div class="card shadow w-50">
        <div class="card-header bg-primary text-white text-center">
            <h3 class="mb-0">Trang quản trị</h3>
        </div>
        <div class="card-body text-center">
            <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-primary btn-block mb-3">
                <i class="fas fa-file-alt"></i> Quản lý bài viết
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-success btn-block">
                <i class="fas fa-users"></i> Quản lý tài khoản
            </a>
        </div>
    </div>
</div>
@endsection
