@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h1>Danh sách bài viết</h1>
    <a href="{{ route('posts.create') }}" class="btn btn-primary mb-2">Tạo mới bài viết</a>
    <form action="{{ route('posts.destroyAll') }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa tất cả?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger mb-2">Xóa tất cả</button>
    </form>
    <table id="posts-table" class="table table-bordered text-center">
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
    <div class="mb-50 ">
        {{ $posts->links() }}
    </div>
</div>
@endsection

{{-- @push('scripts')
<script>
    $(document).ready(function() {
        $('#posts-table').DataTable();
    });
</script>
@endpush --}}
