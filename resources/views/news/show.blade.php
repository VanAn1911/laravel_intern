{{-- filepath: resources/views/news/show.blade.php --}}
@extends('adminlte::page')

@section('content')
<div class="container">
    <h2>{{ $post->title }}</h2>
    <div class="text-muted mb-2">{{ format_datetime($post->publish_date) }}</div>
    <div class="mb-3">{{ $post->description }}</div>

    @if($post->thumbnail)
        <img src="{{ $post->thumbnail }}" alt="{{ $post->title }}" class="img-fluid mb-3">
    @endif

    <div>{!! $post->content !!}</div>

    @auth
        {{-- Like / Dislike --}}
        <div class="my-3">
            <form method="POST" action="{{ route('news.like', $post->slug) }}" style="display:inline">
                @csrf
                <button class="btn btn-success">👍 Like</button>
            </form>
            <form method="POST" action="{{ route('news.dislike', $post->slug) }}" style="display:inline">
                @csrf
                <button class="btn btn-danger">👎 Dislike</button>
            </form>
        </div>

        {{-- Comment Form --}}
        <form method="POST" action="{{ route('news.comment', $post->slug) }}">
            @csrf
            <div class="mb-3">
                <label for="comment">Bình luận:</label>
                <textarea name="content" rows="3" class="form-control" required></textarea>
            </div>
            <button class="btn btn-primary">Gửi bình luận</button>
        </form>
    @endauth

    {{-- Comment List --}}
    <hr>
    <h4>Bình luận:</h4>
    @foreach ($post->comments as $comment)
        <div class="border p-2 mb-2">
            <strong>{{ $comment->user->name }}</strong> - <small>{{ $comment->created_at->diffForHumans() }}</small>
            <div>{{ $comment->content }}</div>
        </div>
    @endforeach
</div>
@endsection
