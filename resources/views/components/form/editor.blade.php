<div class="mb-3">
    <label for="{{ $name }}">
        {{ $label }}
        @if($required)<span style="color: red">*</span>@endif
    </label>
    <textarea name="{{ $name }}" class="form-control summernote @error($name) is-invalid @enderror">{{ old($name, $value) }}</textarea>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
@push('js')
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 300,
                toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            });
        });
    </script>
@endpush