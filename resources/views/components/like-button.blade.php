{{-- filepath: resources/views/components/like-button.blade.php --}}
<form method="POST" action="{{ route('news.toggle-like') }}" class="like-form d-inline">
    @csrf
    <input type="hidden" name="type" value="{{ strtolower(class_basename($likeable)) }}">
    <input type="hidden" name="id" value="{{ $likeable->id }}">
    <input type="hidden" name="is_like" value="{{ $isLike ? 1 : 0 }}">
    <button
        type="submit"
        class="btn btn-sm btn-like btn-outline-{{ $isLike ? 'success' : 'danger' }} {{ $likeable->likes->where('user_id', auth()->id())->where('is_like', $isLike)->isNotEmpty() ? 'active' : '' }}"
    >
        {{ $isLike ? '👍' : '👎' }}
        (<span class="like-count">{{ $likeable->likes->where('is_like', $isLike)->count() }}</span>)
    </button>
</form>
