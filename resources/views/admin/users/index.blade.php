@extends('layouts.app')

@section('content')
    <div class = "container">
        <x-alert />
        <h2>Quản lý người dùng</h2>
        <form id="filter-form" class="row mb-3 align-items-end">
            <x-form.input name="name" label="Tên" class="col-md-3" />
            <x-form.input name="email" label="Email" class="col-md-3" />
            <x-form.enum-select name="status" label="Trạng thái" enumClass="\App\Enums\UserStatus" :includeAllOption="true" class="col-md-3" />

            <div class="col-md-3 row mb-3">
                <button type="submit" class="btn btn-primary w-100 form-control">Tìm kiếm</button>
            </div>
        </form>
        <x-table id="userTable" :headers="['#', 'Tên', 'Email', 'Địa chỉ', 'Trạng thái', 'Hành động']">
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
            if ($.fn.DataTable.isDataTable("#userTable")) {
                $("#userTable").DataTable().destroy();
            }

            const editUrl = "{{ route('admin.users.edit', ['user' => 'ID_PLACEHOLDER']) }}";
            const lockUrl = "{{ route('admin.users.lock', ['user' => 'ID_PLACEHOLDER']) }}";
            var table = $("#userTable").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: @json(route('admin.users.index')),
                    data: function (d) {
                        // Lấy dữ liệu từ form filter
                        d.name = $('#filter-form input[name="name"]').val();
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
                    { data: 'name', name: 'name', orderable: true, searchable: true },
                    { data: 'email', name: 'email', orderable: true, searchable: true },
                    { data: 'address', name: 'address', orderable: true, searchable: true,
                        render: function (data, type, row) {
                            const maxLength = 20;
                            if (typeof data !== 'string') return '';
                            return data.length > maxLength
                                ? `<span title="${data}">${data.substring(0, maxLength)}...</span>`
                                : data;
                        }
                     },
                    {
                        data: 'status', name: 'status', orderable: false, searchable: false,
                        render: function (data) {
                            return `<span class="badge bg-${data.color}">${data.label}</span>`;
                        }
                    },
                    { data: 'id', name: 'action', orderable: false, searchable: false,
                        render: function (id, type, row) {
                            let actions = `
                                <a href="${editUrl.replace('ID_PLACEHOLDER', id)}" class="btn btn-warning btn-sm">Sửa</a> 
                                <a href="#" class="btn btn-danger btn-sm lock-btn" data-id="${id}">Khóa</a>
                            `;
                            @if(Auth::check())
                                if (row.email === '{{ Auth::user()->email }}') actions = '';
                            @endif
                            return actions;
                        }
                    }
                ]
            });
            $(document).on('click', '.lock-btn', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                if(confirm('Bạn có chắc muốn khóa user này?')) {
                    $.ajax({
                        url: lockUrl.replace('ID_PLACEHOLDER', id),
                        type: 'POST',
                        data: {_token: '{{ csrf_token() }}'},
                        success: function(res) {
                            alert(res.message);
                            $('#userTable').DataTable().ajax.reload(null, false);
                        }
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