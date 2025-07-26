{{-- filepath: resources/views/posts/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tạo bài viết</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
        @csrf
        <x-form.input name="title" label="Tiêu đề" :required="true" />
        <x-form.input name="description" label="Mô tả" :required="true" />
        <x-form.editor name="content" label="Nội dung" :required="true" :value="$post->content ?? ''" />
        <x-form.input name="publish_date" label="Ngày đăng" type="datetime-local" :required="true" />
        <x-form.input name="image" label="Hình ảnh" type="file" :required="true" />
        
        <button type="submit" class="btn btn-primary">Tạo bài viết</button>
    </form>
</div>
@endsection
