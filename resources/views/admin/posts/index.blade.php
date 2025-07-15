@extends('adminlte::page')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <h2>Quản lý bài viết</h2>
    <form action="{{ route('posts.destroyAll') }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa tất cả?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger mb-2">Xóa tất cả</button>
    </form>
    <table id="admin-posts-table" class="table table-bordered text-center">
        {{-- <colgroup>
            <col style="width: 5%">
            <col style="width: 20%">
            <col style="width: 10%">
            <col style="width: 25%">
            <col style="width: 10%">
            <col style="width: 10%">
            <col style="width: 20%">
        </colgroup> --}}
        <thead>
            <tr>
                <th>#</th>
                <th>Tiêu đề</th>
                <th>Email</th>
                <th>Hình ảnh</th>
                <th>Mô tả</th>
                <th>Ngày đăng</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        
        </tbody>
    </table>
</div>
@endsection

@push('js')
<script src="{{ asset('js/admin-post-datatable.js') }}"></script>
</script>
@endpush