{{-- filepath: resources/views/news/show.blade.php --}}
@extends('adminlte::page')

@section('content')
<div class="container">
    <h2>{{ $post->title }}</h2>
    <div class="text-muted mb-2">{{ format_datetime($post->publish_date) }}</div>
    <div class="mb-3">{{ $post->description }}</div>

    @if($post->thumbnail)
        <img src="{{ $post->thumbnail }}" alt="{{ $post->title }}" class="img-fluid mb-3" style="width: 70%; height: auto; display: block; margin-left: auto; margin-right: auto;">
    @endif

    <div>{!! $post->content !!}</div>

    @auth
        {{-- Like / Dislike Post --}}
        <div class="my-3">
            <x-like-button :likeable="$post" :is-like="true" />
            <x-like-button :likeable="$post" :is-like="false" />
        </div>

        {{-- Comment Form --}}
        <form method="POST" action="{{ route('news.comment', $post->slug) }}" class="comment-form">
            @csrf
            <x-form.textarea name="content" label="Bình luận" required></x-form.textarea>
            <button class="btn btn-primary">Gửi bình luận</button>
        </form>
    @endauth

    {{-- Display Comments --}}
    <hr>
    <h3 class="mt-4">Bình luận</h3>
    @foreach ($post->comments->whereNull('parent_id')->sortByDesc('created_at') as $comment)
        <div class="border p-2 mb-2">
            <strong>{{ $comment->user->name }}</strong>
            <small>{{ $comment->created_at->diffForHumans() }}</small>
            <div>{{ $comment->content }}</div>

            {{-- Like / Dislike --}}
            @auth
                <x-like-button :likeable="$comment" :is-like="true" />
                <x-like-button :likeable="$comment" :is-like="false" />
            @endauth

            {{-- Reply Form --}}
            @auth
            <form method="POST" action="{{ route('news.comment.reply', $comment) }}" class="reply-form mt-2" data-comment-id="{{ $comment->id }}">
                @csrf
                <x-form.textarea name="content" label="Trả lời" required></x-form.textarea>
                <button class="btn btn-primary btn-sm">Trả lời</button>
            </form>
            @endauth

            {{-- Hiển thị phản hồi --}}
            @foreach ($comment->replies as $reply)
                <div class="border-start ps-5 mt-2">
                    <strong>{{ $reply->user->name }}</strong>
                    <small>{{ $reply->created_at->diffForHumans() }}</small>
                    <div>{{ $reply->content }}</div>

                    {{-- Like / Dislike reply --}}
                    @auth
                        <x-like-button :likeable="$reply" :is-like="true" />
                        <x-like-button :likeable="$reply" :is-like="false" />
                    @endauth
                </div>
            @endforeach
        </div>
    @endforeach
</div>
@endsection
@push('js')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-like').forEach(btn => {
        btn.addEventListener('click', async e => {
            e.preventDefault();
            const type = btn.dataset.type;
            const id = btn.dataset.id;
            const isLike = btn.dataset.isLike;

            const formData = new FormData();
            formData.append('type', type);
            formData.append('id', id);
            formData.append('is_like', isLike);
            formData.append('_token', '{{ csrf_token() }}');

            const res = await fetch('{{ route("news.toggle-like") }}', {
                method: 'POST',
                body: formData,
            });

            if (res.ok) {
                const data = await res.json();
                console.log('Data trả về:', data);

                // Cập nhật cả số like & dislike
                const allButtons = document.querySelectorAll(`.btn-like[data-type="${type}"][data-id="${id}"]`);
                allButtons.forEach(button => {
                    const isLikeBtn = button.dataset.isLike === '1';
                    const countSpan = button.querySelector('.like-count');

                    // Cập nhật số
                    if (countSpan) {
                        countSpan.textContent = isLikeBtn ? data.like : data.dislike;
                    }

                    // Cập nhật class active
                    if (parseInt(button.dataset.isLike) === data.current_like) {
                        button.classList.add('active');
                    } else {
                        button.classList.remove('active');
                    }
                });

            } else {
                alert('Thao tác thất bại!');
            }     
        });
    });
});
</script>

@endpush
