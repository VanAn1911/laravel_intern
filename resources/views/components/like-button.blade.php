@php
    $userLiked = $likeable->likes->where('user_id', auth()->id())->where('is_like', $isLike)->isNotEmpty();
@endphp

<button
    class="btn btn-sm btn-like btn-outline-{{ $isLike ? 'success' : 'danger' }}
        {{ $userLiked ? 'active' : '' }}"
    data-type="{{ strtolower(class_basename($likeable)) }}"
    data-id="{{ $likeable->id }}"
    data-is-like="{{ $isLike ? 1 : 0 }}"
>
    {{ $isLike ? '👍' : '👎' }}
    (<span class="like-count">{{ $likeable->likes->where('is_like', $isLike)->count() }}</span>)
</button>
