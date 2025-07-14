
<?php
use App\Enums\UserStatus;
?>
@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <h2>Quản lý người dùng</h2>
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="input-group mb-3">
                <input type="text" name="keyword" class="form-control" placeholder="Tìm tên hoặc email" value="{{ request('keyword') }}">
                <button class="btn btn-primary">Tìm</button>
            </div>
        </form>

        <table class="table table-bordered text-center" id="users-table">
            <thead>
                <tr>
                    <th style="width: 20%">Họ tên</th>
                    <th style="width: 20%">Email</th>
                    <th style="width: 25%">Địa chỉ</th>
                    <th style="width: 15%">Trạng thái</th>
                    <th style="width: 20%">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->address }}</td>
                        <td>
                            @php
                                $statusEnum = ($user->status);
                            @endphp
                            @switch($statusEnum)
                                @case(UserStatus::PENDING)
                                    <span class="badge bg-secondary">{{ $statusEnum->label() }}</span>
                                    @break
                                @case(UserStatus::APPROVED)
                                    <span class="badge bg-success">{{ $statusEnum->label() }}</span>
                                    @break
                                @case(UserStatus::REJECTED)
                                    <span class="badge bg-danger">{{ $statusEnum->label() }}</span>
                                    @break
                                @case(UserStatus::BLOCKED)
                                    <span class="badge bg-dark">{{ $statusEnum->label() }}</span>
                                    @break
                                @default
                                    <span class="badge bg-warning">Không rõ</span>
                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Sửa</a>
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
    $('#users-table').DataTable({
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