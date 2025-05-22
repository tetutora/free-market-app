@props([
    'label',
    'name',
    'type' => 'text',
    'value' => '',
    'error' => null,
])

<div class="form-group">
    <label for="{{ $name }}" class="form-group__label">{{ $label }}</label>
    <input
        type="{{ $type }}"
        class="form-control"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
    >
    @if ($error)
        <div class="text-danger">{{ $error }}</div>
    @endif
</div>
