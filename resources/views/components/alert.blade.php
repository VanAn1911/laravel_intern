{{-- khai báo biến đầu vào cho component alert --}}
@props(['message', 'type'])
@if ($message)
    <div class="alert alert-{{ $type }}">
        {{ $message }}
    </div>
@endif