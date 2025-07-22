
@php
    use App\Enums\RoleEnum;
@endphp
@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>Sửa bài viết</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    {{-- @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif --}}
    <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <x-form.input name="title" label="Tiêu đề" :value="$post->title" :required="true" />
        <x-form.input name="description" label="Mô tả" :value="$post->description" :required="true" />
        <x-form.editor name="content" label="Nội dung" :value="$post->content" :required="true" />
        <x-form.input name="publish_date" label="Ngày đăng" type="datetime-local" :value="format_datetime($post->publish_date)" :required="true" />
        <x-form.image-preview label="Hình ảnh hiện tại:" :src="$post->thumbnail ?? null" />
        <x-form.input name="image" label="Thay đổi hình ảnh" type="file" />
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 300,  // Chiều cao khung nhập
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endpush