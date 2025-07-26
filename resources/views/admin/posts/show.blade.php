@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $post->title }}</h2>
    <div class="text-muted mb-2">
        {{ format_datetime($post->publish_date) }}
    </div>
    <div class="mb-3">{{ $post->description }}</div>
    @if($post->thumbnail)
        <img src="{{ $post->thumbnail }}" alt="{{ $post->title }}" class="img-fluid mb-3">
    @endif
    <div>{!! $post->content !!}</div>
</div>
{{-- Hiển thị Like --}}
<div class="mt-4">
    👍 Thích: {{ $post->likes->where('is_like', true)->count() }}
    👎 Không thích: {{ $post->likes->where('is_like', false)->count() }}
</div>

{{-- Hiển thị Comment --}}
<div class="mt-4">
    <h4>Bình luận</h4>
    @forelse ($post->comments->where('parent_id', null) as $comment)
        <div class="border p-2 mb-2">
            <strong>{{ $comment->user->name }}</strong>
            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
            <div>{{ $comment->content }}</div>

            {{-- Hiển thị reply --}}
            @foreach ($comment->replies as $reply)
                <div class="ps-4 border-start mt-2">
                    <strong>{{ $reply->user->name }}</strong>
                    <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                    <div>{{ $reply->content }}</div>
                </div>
            @endforeach
        </div>
    @empty
        <p>Chưa có bình luận.</p>
    @endforelse
</div>

@endsection