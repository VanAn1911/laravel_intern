@extends('adminlte::page')

@section('content')
<div class="container">
    <x-alert />
    <h2>Quản lý bài viết</h2>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary mb-2">Tạo mới bài viết</a>
    <form action="{{ route('posts.destroyAll') }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa tất cả?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger mb-2">Xóa tất cả</button>
    </form>
    <form id="filter-form" class="row mb-3 align-items-end">
        <x-form.input name="title" label="Tiêu đề" class="col-md-3" />
        <x-form.input name="email" label="Email" class="col-md-3" />
        <x-form.enum-select name="status" label="Trạng thái" enumClass="\App\Enums\PostStatus" :includeAllOption="true" class="col-md-3" />

        <div class="col-md-3 row mb-3">
            <button type="submit" class="btn btn-primary w-100 form-control">Tìm kiếm</button>
        </div>
    </form>

    <x-table id="admin-posts-table" :headers="['#', 'Tiêu đề', 'Email', 'Hình ảnh', 'Mô tả', 'Ngày đăng', 'Trạng thái', 'Hành động']">
        <tbody></tbody>
    </x-table>

</div>
@endsection

@push('js')
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

        const showUrl = "{{ route('admin.posts.show', ['post' => 'ID_PLACEHOLDER']) }}";
        const editUrl = "{{ route('admin.posts.edit', ['post' => 'ID_PLACEHOLDER']) }}";
        const deleteUrl = "{{ route('admin.posts.destroy', ['post' => 'ID_PLACEHOLDER']) }}";
        var table = $("#admin-posts-table").DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: @json(route('admin.posts.index')),
                data: function (d) {
                    // Lấy dữ liệu từ form filter
                    d.title = $('#filter-form input[name="title"]').val();
                    d.email = $('#filter-form input[name="email"]').val();
                    d.status = $('#filter-form select[name="status"]').val();
                },
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
                        let show = `<a href="${showUrl.replace('ID_PLACEHOLDER', id)}" class="btn btn-info btn-sm">Show <i class="fas fa-eye"></i></a>`;
                        let edit = `<a href="${editUrl.replace('ID_PLACEHOLDER', id)}" class="btn btn-warning btn-sm">Edit <i class="fas fa-edit"></i></a>`;
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
                url: deleteUrl.replace('ID_PLACEHOLDER', id),
                type: 'DELETE',
                data: {_token: $('meta[name="csrf-token"]').attr('content')},
                success: function(res) {
                    if(res.success) {
                        alert(res.message || 'Xóa bài viết thành công');
                        $('#admin-posts-table').DataTable().ajax.reload(null, false);
                    } else {
                    alert(res.message || 'Xóa thất bại');
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
                        $('#admin-posts-table').DataTable().ajax.reload(null, false);
                    }
                },
            });
        }
    });

    // Khi submit form filter thì reload bảng
    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });
});
</script>
@endpush