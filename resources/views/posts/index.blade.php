@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('login_success'))
        <div class="alert alert-success">
            Đăng nhập thành công
        </div>
    @endif
    <h1>Danh sách bài viết</h1>
    <p>Tiêu đề từ URL: {!! request('title') !!}</p> <!-- LỖ HỔNG XSS -->

    <form method="GET" action="{{ route('posts.index') }}">
        <div class="mb-3">
            <label for="title" class="form-label">Nhập tiêu đề:</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ request('title') }}">
        </div>
        <button type="submit" class="btn btn-primary">Gửi</button>
    </form>

    <p>Tiêu đề từ input: {!! request('title') !!}</p> <!-- LỖ HỔNG XSS -->
    
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Tiêu đề</th>
                <th>Tác giả</th>
                <th>Ngày đăng</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Bài viết mẫu 1</td>
                <td>Admin</td>
                <td>2025-06-19</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Bài viết mẫu 2</td>
                <td>User</td>
                <td>2025-06-18</td>
            </tr>
        </tbody>
    </table>
</div>
<script>
    // Hiển thị alert đăng nhập thành công nếu có session
    @if(session('login_success'))
        document.getElementById('login-success').style.display = 'block';
    @endif
</script>
@endsection
