@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <h2>Quản lý bài viết</h2>
    <form method="GET" class="row mb-3">
        <div class="col">
            <input type="text" name="title" class="form-control" placeholder="Tìm theo tiêu đề" value="{{ request('title') }}">
        </div>
        <div class="col">
            <input type="text" name="email" class="form-control" placeholder="Tìm theo email user" value="{{ request('email') }}">
        </div>
        <div class="col">
            <button class="btn btn-primary">Tìm kiếm</button>
        </div>
    </form>
    <a href="{{ route('posts.create') }}" class="btn btn-primary mb-2">Tạo mới bài viết</a>
    <form action="{{ route('posts.destroyAll') }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa tất cả?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger mb-2">Xóa tất cả</button>
    </form>
    <table id="posts-table" class="table table-bordered text-center">
        <colgroup>
            <col style="width: 5%">
            <col style="width: 20%">
            <col style="width: 10%">
            <col style="width: 25%">
            <col style="width: 10%">
            <col style="width: 10%">
            <col style="width: 20%">
        </colgroup>
        <thead>
            <tr>
                <th>#</th>
                <th>Tiêu đề</th>
                <th>Hình ảnh</th>
                <th>Mô tả</th>
                <th>Ngày đăng</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        @foreach($posts as $i => $post)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $post->title }}</td>
                <td><img src="{{ $post->thumbnail }}" alt="{{ $post->title }}" class="img-thumbnail" width="100"></td>
                <td>{{ $post->description }}</td>
                <td>{{ $post->publish_date ? $post->publish_date->format('d/m/Y') : 'Chưa đặt ngày' }}</td>
                <td>
                    <span class="badge 
                        @switch($post->status)
                            @case(0) bg-secondary @break
                            @case(1) bg-success @break
                            @case(2) bg-danger @break
                            @default bg-dark
                        @endswitch
                    ">
                        @switch($post->status)
                            @case(0) Bài mới @break
                            @case(1) Đã phê duyệt @break
                            @case(2) Từ chối @break
                            @default Không rõ
                        @endswitch
                    </span>
                </td>

                <td>
                    <a href="{{ route('posts.show', $post) }}" class="btn btn-info btn-sm">Show <i class="fas fa-eye"></i></a>
                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning btn-sm">Edit <i class="fas fa-edit"></i></a>
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete <i class="fas fa-trash-alt"></i></button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#posts-table').DataTable({
        pageLength: 5,
        paging: true,
        searching: false,
        info: false,
        ordering: false,
        lengthChange: false,
        language: {
            paginate: {
                previous: 'Trước',
                next: 'Sau'
            },
            emptyTable: "Không có dữ liệu"
        }
    });
});
</script>
@endpush