<div {{ $attributes->merge(['class' => 'mb-3']) }}>
    <label>{{ $label }}</label>
    <select name="{{ $name }}" class="form-control">
        @if(!empty($includeAllOption) && $includeAllOption)
            <option value="">{{ 'Tất cả' }}</option>
        @endif
        @foreach($enumClass::cases() as $case)
            <option value="{{ $case->value }}" {{ $selected === $case ? 'selected' : '' }}>
                {{ method_exists($case, 'label') ? $case->label() : $case->name }}
            </option>
        @endforeach
    </select>
</div>
