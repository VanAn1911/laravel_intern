{{-- filepath: resources/views/admin/users/edit.blade.php --}}
@extends('adminlte::page')

@section('content')
<div class="container">
    <h2>Cập nhật thông tin user</h2>
    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PUT')
        <x-form.input name="first_name" label="Họ" :value="$user->first_name" :required="true" />
        <x-form.input name="last_name" label="Tên" :value="$user->last_name" :required="true" />
        <x-form.input name="address" label="Địa chỉ" :value="$user->address" />
        <x-form.enum-select name="status" label="Trạng thái" enumClass="\App\Enums\UserStatus" :selected="$user->status" />
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection