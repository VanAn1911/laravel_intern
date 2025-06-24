{{-- khai báo biến đầu vào cho component alert --}}
@props(['message', 'type'])
<div class="alert alert-{{ $type }}" role="alert">
    {{ $message }}
    <div>{{ $slot }}</div>
</div>