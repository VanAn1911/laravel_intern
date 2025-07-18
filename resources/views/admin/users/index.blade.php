@extends('adminlte::page')

@section('content')
    <div class = "container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <h2>Quản lý người dùng</h2>
        <table id="userTable" class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Địa chỉ</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
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

            $("#userTable").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.users.index') }}',
                    type: 'GET'
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
                            let editUrl = '/admin/users/' + id + '/edit';
                            let actions = `
                                <a href="${editUrl}" class="btn btn-warning btn-sm">Sửa</a> 
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
                        url: '{{ route('admin.users.lock', ['user' => ':id']) }}'.replace(':id', id),
                        type: 'POST',
                        data: {_token: '{{ csrf_token() }}'},
                        success: function(res) {
                            alert(res.message);
                            $('#userTable').DataTable().ajax.reload(null, false);
                        }
                    });
                }
            });
        });
    </script>
@endpush