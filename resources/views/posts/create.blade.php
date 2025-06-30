{{-- filepath: resources/views/posts/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tạo bài viết</h1>
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
    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Tiêu đề <span style="color: red">*</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
        </div>
        <div class="mb-3">
            <label>Mô tả</label>
            <input type="text" name="description" class="form-control" value="{{ old('description') }}">
        </div>
        <div class="mb-3">
            <label for="content">Nội dung <span style="color: red">*</span></label>
            <textarea name="content" class="form-control summernote">{{ old('content', $post->content ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Thumbnail</label>
            <input type="file" name="thumbnail" class="form-control">
        </div>
        <div class="mb-3">
            <label>Ngày đăng</label>
            <input type="datetime-local" name="publish_date" class="form-control" value="{{ old('publish_date') }}">
        </div>
        <button type="submit" class="btn btn-primary">Tạo bài viết</button>
    </form>
</div>
@endsection

@push('scripts')
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
