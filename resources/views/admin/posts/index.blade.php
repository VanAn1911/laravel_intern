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
{{-- <script src="{{ asset('js/admin-post-datatable.js') }}"></script> --}}
<script>
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable("#admin-posts-table")) {
            $("#admin-posts-table").DataTable().destroy();
        }

        $("#admin-posts-table").DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: '{{ route("admin.posts.index") }}',
                dataSrc: function (json) {
                    console.log("DataTables response:", json);
                    return json.data;
                },
                error: function (xhr, error, thrown) {
                    console.error("DataTables AJAX error:", xhr, error, thrown);
                },
            },
            pageLength: 5,
            lengthMenu: [[5, 10, 25], [5, 10, 25]],
            columns: [
                { data: null,name: 'stt',orderable: false,searchable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },

                { data: "title", name: "title", orderable: true, searchable: true,
                    render: function (data, type, row) {
                        const maxLength = 20;
                        if (typeof data !== 'string') return '';
                        return data.length > maxLength
                            ? `<span title="${data}">${data.substring(0, maxLength)}...</span>`
                            : data;
                    }
                 },
                { data: "user.email", name: "user.email", orderable: true, searchable: true },
                {
                    data: "thumbnail",
                    name: "thumbnail",
                    render: function (data) {
                        return `<img src="${data}" alt="thumbnail" style="max-width: 50px;">`;
                    },
                },
                { data: "description", name: "description",
                    render: function (data, type, row) {
                        const maxLength = 20;
                        if (typeof data !== 'string') return '';
                        return data.length > maxLength
                            ? `<span title="${data}">${data.substring(0, maxLength)}...</span>`
                            : data;
                    }
                 },
                { data: "publish_date", name: "publish_date" },
                { data: 'status', name: 'status', orderable: false, searchable: false,
                    render: function (data) {
                    return `<span class="badge bg-${data.color}">${data.label}</span>`;
                    }
                },
                { data: 'id', name: 'action', orderable: false, searchable: false,
                    render: function (id, type, row) {
                        let show = `<a href="/admin/posts/${id}" class="btn btn-info btn-sm">Show <i class="fas fa-eye"></i></a>`;
                        let edit = `<a href="/admin/posts/${id}/edit" class="btn btn-warning btn-sm">Edit <i class="fas fa-edit"></i></a>`;
                        let del = `<button class="btn btn-danger btn-sm btn-delete" data-id="${id}">Delete <i class="fas fa-trash-alt"></i></button>`;
                        return show + ' ' + edit + ' ' + del;
                    }
                }
            ],
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