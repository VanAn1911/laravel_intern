{{-- filepath: resources/views/admin/users/edit.blade.php --}}
@extends('adminlte::page')

@section('content')
<div class="container">
    <h2>Cập nhật thông tin user</h2>
    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Họ</label>
            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                   value="{{ old('first_name', $user->first_name) }}" required>
            @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label>Tên</label>
            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                   value="{{ old('last_name', $user->last_name) }}" required>
            @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label>Địa chỉ</label>
            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                   value="{{ old('address', $user->address) }}">
            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label>Trạng thái</label>
            <select name="status" class="form-control @error('status') is-invalid @enderror">
                @foreach(\App\Enums\UserStatus::cases() as $status)
                    <option value="{{ $status->value }}" {{ $user->status === $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>
            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection