<div {{ $attributes->merge(['class' => 'mb-3']) }}>
    <label for="{{ $name }}">
        {{ $label }}
        @if($required)<span style="color: red">*</span>@endif
    </label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        class="form-control @error($name) is-invalid @enderror"
        value="{{ old($name, $value) }}"
    >
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
