@extends('adminlte::page')

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
        </tbody>
    </table>
</div>
@endsection

@push('js')
{{-- <script src="{{ asset('js/post-datatable.js') }}"></script> --}}
<script>
// AJAX Scripts cho delete và deleteAll không reload trang

// CSRF token setup
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Delete all posts
function deleteAllPosts() {
    if (confirm('Bạn có chắc chắn muốn xóa tất cả?')) {
        $.ajax({
            url: '{{ route("posts.destroyAll") }}',
            type: 'POST',
            data: { 
                method: 'DELETE', 
                _token: '{{ csrf_token() }}' 
            },
            success: function(response) {
                if (response.success) {
                    // Reload DataTable hoặc trang
                    if (typeof table !== 'undefined') {
                        table.ajax.reload();
                    } else {
                        location.reload();
                    }
                    
                    // Hiển thị thông báo thành công
                    if (typeof toastr !== 'undefined') {
                        toastr.success('Xóa tất cả bài viết thành công!');
                    } else {
                        alert('Xóa tất cả bài viết thành công!');
                    }
                }
            },
            error: function(xhr) {
                console.error('Lỗi khi xóa tất cả bài viết:', xhr);
                if (typeof toastr !== 'undefined') {
                    toastr.error('Có lỗi xảy ra khi xóa tất cả bài viết!');
                } else {
                    alert('Có lỗi xảy ra khi xóa tất cả bài viết!');
                }
            }
        });
    }
}

$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#posts-table')) {
        $('#posts-table').DataTable().destroy();
    }
    $('#posts-table').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: {{ route('posts.index') }},
            dataSrc: function(json) {
                return json.data;
            }
        },
        pageLength: 5,
        lengthMenu: [[5, 10, 25], [5, 10, 25]],
        columns: [
            { data: null,name: 'stt',orderable: false,searchable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
            { data: 'title', name: 'title',
                render: function (data, type, row) {
                    const maxLength = 20;
                    if (typeof data !== 'string') return '';
                    return data.length > maxLength
                        ? `<span title="${data}">${data.substring(0, maxLength)}...</span>`
                        : data;
                }
             },
            { data: 'thumbnail', name: 'thumbnail', orderable: false, searchable: false,
                render: function (data) {
                    return data ? `<img src="${data}" alt="thumbnail" style="max-width: 50px;">` : '';
                }
            },
            { data: 'description', name: 'description',
                render: function (data, type, row) {
                    const maxLength = 20;
                    if (typeof data !== 'string') return '';
                    return data.length > maxLength
                        ? `<span title="${data}">${data.substring(0, maxLength)}...</span>`
                        : data;
                }
             },
            { data: 'publish_date', name: 'publish_date' },
            { data: 'status', name: 'status', orderable: false, searchable: false,
                render: function (data) {
                   return `<span class="badge bg-${data.color}">${data.label}</span>`;
                }
            },
            { data: 'id', name: 'action', orderable: false, searchable: false,
                render: function (id, type, row) {
                    let show = `<a href="/posts/${id}" class="btn btn-info btn-sm">Show <i class="fas fa-eye"></i></a>`;
                    let edit = `<a href="/posts/${id}/edit" class="btn btn-warning btn-sm">Edit <i class="fas fa-edit"></i></a>`;
                    let del = `<button class="btn btn-danger btn-sm btn-delete" data-id="${id}">Delete <i class="fas fa-trash-alt"></i></button>`;
                    return show + ' ' + edit + ' ' + del;
                }
            },
        ]
    });

    $(document).on('click', '.btn-delete', function() {
        let id = $(this).data('id');
        if(confirm('Bạn có chắc muốn xóa?')) {
            $.ajax({
                url: '/posts/' + id,
                type: 'DELETE',
                data: {_token: $('meta[name="csrf-token"]').attr('content')},
                success: function(res) {
                    if(res.success) {
                        $('#posts-table').DataTable().ajax.reload(null, false);
                    }
                }
            });
        }
    });

    $(document).on('click', '.btn-delete-all', function() {
        if(confirm('Bạn có chắc chắn muốn xóa tất cả bài viết?')) {
            $.ajax({
                url: '/posts/delete-all',
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    if(res.success) {
                        $('#posts-table').DataTable().ajax.reload(null, false);
                    }
                },
            });
        }
    });
});
</script>

@endpush
