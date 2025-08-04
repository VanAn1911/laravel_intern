{{-- filepath: resources/views/news/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $post->title }}</h2>
    <div class="text-muted mb-2">{{ \App\Helpers\FormatHelper::datetime($post->publish_date) }}</div>
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
    @foreach ($comments as $comment)
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
    document.querySelectorAll('.like-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            const res = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (res.ok) {
                const data = await res.json();

                const type = formData.get('type');
                const id = formData.get('id');

                // Tìm tất cả form like/dislike của cùng 1 đối tượng (post/comment/reply)
                document.querySelectorAll(`.like-form input[name="type"][value="${type}"]`).forEach(input => {
                    const form = input.closest('form');
                    const formId = form.querySelector('input[name="id"]').value;

                    if (formId == id) {
                        const btn = form.querySelector('.btn-like');
                        const isLikeBtn = Boolean(Number(form.querySelector('input[name="is_like"]').value));
                        const countSpan = btn.querySelector('.like-count');

                        // Cập nhật số đếm like/dislike
                        if (countSpan) {
                            countSpan.textContent = isLikeBtn ? data.like : data.dislike;
                        }

                        // Xóa class active tất cả nút trước khi set lại
                        btn.classList.remove('active');
                        if (data.current_like !== null) {
                            if ((isLikeBtn && data.current_like === 1) || (!isLikeBtn && data.current_like === 0)) {
                                btn.classList.add('active');
                            }
                        }
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
