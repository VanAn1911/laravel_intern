<div class="mb-3">
    <label for="{{ $name }}">
        {{ $label }}
        @if($required)<span style="color: red">*</span>@endif
    </label>
    <textarea
        name="{{ $name }}"
        class="form-control @error($name) is-invalid @enderror"
    >{{ old($name, $value) }}</textarea>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
