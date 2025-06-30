{{-- filepath: resources/views/posts/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Sửa bài viết</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
    <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Tiêu đề <span style="color: red">*</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $post->title) }}" required>
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label>Mô tả</label>
            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
                   value="{{ old('description', $post->description) }}">
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label>Nội dung <span style="color: red">*</label>
            <textarea name="content" class="form-control summernote @error('content') is-invalid @enderror">{{ old('content', $post->content) }}</textarea>
            @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label>Ngày đăng</label>
            <input type="datetime-local" name="publish_date" class="form-control @error('publish_date') is-invalid @enderror"
                   value="{{ old('publish_date', $post->publish_date ? $post->publish_date->format('Y-m-d\TH:i') : '') }}">
            @error('publish_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label>Thumbnail hiện tại:</label><br>
            @if($post->thumbnail)
                <img src="{{ $post->thumbnail }}" width="120">
            @endif
        </div>
        <div class="mb-3">
            <label>Thay ảnh thumbnail</label>
            <input type="file" name="thumbnail" class="form-control">
        </div>
        @if(auth()->user()->role === 'admin')
        <div class="mb-3">
            <label>Trạng thái</label>
            <select name="status" class="form-control">
                <option value="0" {{ $post->status == 0 ? 'selected' : '' }}>Mới</option>
                <option value="1" {{ $post->status == 1 ? 'selected' : '' }}>Cập nhật</option>
                <option value="2" {{ $post->status == 2 ? 'selected' : '' }}>Khác</option>
            </select>
        </div>
        @endif
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.summernote').summernote();
    });
</script>
@endpush