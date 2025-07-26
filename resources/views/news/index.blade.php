{{-- filepath: resources/views/news/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">TIN MỚI</h2>
    @foreach($posts as $post)
        <div class="row mb-4">
            <div class="col-md-3">
                <a href="{{ route('news.show', $post->slug) }}">
                    <img src="{{ $post->thumbnail }}" alt="{{ $post->title }}" class="img-fluid rounded">
                </a>
            </div>
            <div class="col-md-9">
                <a href="{{ route('news.show', $post->slug) }}">
                    <h5 class="mb-1" style="font-weight:bold;">{{ $post->title }}</h5>
                </a>
                <div class="text-muted mb-1" style="font-size:13px;">
                    {{ format_datetime($post->publish_date) }}
                </div>
                <div>{{ $post->description }}</div>
            </div>
        </div>
        <hr>
    @endforeach

    <div>
        {{ $posts->links() }}
    </div>
</div>
@endsection

