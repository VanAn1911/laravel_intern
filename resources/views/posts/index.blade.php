@extends('layouts.app')

@section('content')
<div class="container">
    <x-alert />

    <h1>Danh sách bài viết</h1>
    <a href="{{ route('posts.create') }}" class="btn btn-primary mb-2">Tạo mới bài viết</a>
    <form action="{{ route('posts.destroyAll') }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa tất cả?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger mb-2">Xóa tất cả</button>
    </form>
    <form id="filter-form" class="row mb-3 align-items-end">
        <x-form.input name="title" label="Tiêu đề" class="col-md-3" />
        <x-form.input name="description" label="Mô tả" class="col-md-3" />
        <x-form.enum-select name="status" label="Trạng thái" enumClass="\App\Enums\PostStatus" :includeAllOption="true"  class="col-md-3" />

        <div class="col-md-3 row mb-3">
            <button type="submit" class="btn btn-primary w-100 form-control">Tìm kiếm</button>
        </div>
    </form>
    <x-table id="posts-table" :headers="['#', 'Tiêu đề', 'Hình ảnh', 'Mô tả', 'Ngày đăng', 'Trạng thái', 'Hành động']">
        <tbody></tbody>
    </x-table>
</div>
@endsection

@push('js')
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#posts-table')) {
        $('#posts-table').DataTable().destroy();
    }

    const showUrl = "{{ route('posts.show', ['post' => 'ID_PLACEHOLDER']) }}";
    const editUrl = "{{ route('posts.edit', ['post' => 'ID_PLACEHOLDER']) }}";
    const deleteUrl = "{{ route('posts.destroy', ['post' => 'ID_PLACEHOLDER']) }}";
    var table = $('#posts-table').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: @json(route('posts.index')),
            data: function (d) {
                // Lấy dữ liệu từ form filter
                d.title = $('#filter-form input[name="title"]').val();
                d.description = $('#filter-form input[name="description"]').val();
                d.status = $('#filter-form select[name="status"]').val();
            },
            dataSrc: function (json) {
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
                    let show = `<a href="${showUrl.replace('ID_PLACEHOLDER', id)}" class="btn btn-info btn-sm">Show <i class="fas fa-eye"></i></a>`;
                    let edit = `<a href="${editUrl.replace('ID_PLACEHOLDER', id)}" class="btn btn-warning btn-sm">Edit <i class="fas fa-edit"></i></a>`;
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
                url: deleteUrl.replace('ID_PLACEHOLDER', id),
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
                url: @json(route('posts.destroyAll')),
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

    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });
});
</script>

@endpush
