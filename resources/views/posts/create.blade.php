{{-- filepath: resources/views/posts/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tạo bài viết</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf
        <x-form.input name="title" label="Tiêu đề" :required="true" />
        <x-form.input name="description" label="Mô tả" :required="true" />
        <x-form.editor name="content" label="Nội dung" :required="true" :value="$post->content ?? ''" />
        <x-form.input name="publish_date" label="Ngày đăng" type="datetime-local" :required="true" />
        <x-form.input name="image" label="Hình ảnh" type="file" />
        <button type="submit" class="btn btn-primary">Tạo bài viết</button>
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
