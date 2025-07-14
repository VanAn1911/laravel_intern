@extends('adminlte::page')

@section('content')
<div class="container">
    <h2>{{ $post->title }}</h2>
    <div class="text-muted mb-2">
        {{ $post->publish_date ? $post->publish_date->format('H:i d/m/Y') : '' }}
    </div>
    <div class="mb-3">{{ $post->description }}</div>
    @if($post->thumbnail)
        <img src="{{ $post->thumbnail }}" alt="{{ $post->title }}" class="img-fluid mb-3">
    @endif
    <div>{!! $post->content !!}</div>
</div>
@endsection