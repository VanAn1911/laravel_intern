<div class="mb-3">
    <label>{{ $label }}</label><br>
    @if($src)
        <img src="{{ $src }}" width="{{ $width }}">
    @else
        <p class="text-muted">Không có ảnh</p>
    @endif
</div>
