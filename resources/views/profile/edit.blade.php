@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Cập nhật hồ sơ</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        <div class="mb-3">
            <label>Họ <span style="color: red">*</span></label>
            <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="form-control @error('first_name') is-invalid @enderror">
            @error('first_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label>Tên <span style="color: red">*</span></label>
            <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="form-control @error('last_name') is-invalid @enderror">
            @error('last_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label>Địa chỉ</label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}" class="form-control @error('address') is-invalid @enderror">
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection