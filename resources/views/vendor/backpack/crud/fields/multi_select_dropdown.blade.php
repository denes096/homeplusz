@php
    $field['allows_null'] = $field['allows_null'] ?? $crud->model::isColumnNullable($field['name']);
    $field['value'] = old_empty_or_null($field['name'], []) ??  $field['value'] ?? $field['default'] ?? [];
    $field['multiple'] = true; // Always multiple for this field type

    // Ensure value is an array
    if (!is_array($field['value'])) {
        $field['value'] = [];
    }

    $fieldId = 'select2-' . uniqid();
@endphp

{{-- multi select dropdown --}}
@include('crud::fields.inc.wrapper_start')

    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

    {{-- Select2 dropdown --}}
    <select id="{{ $fieldId }}"
            name="{{ $field['name'] }}[]"
            @include('crud::fields.inc.attributes', ['default_class' => 'form-control'])
            multiple
            data-placeholder="{{ $field['placeholder'] ?? 'Válassz...' }}">
        @if (isset($field['options']) && is_array($field['options']))
            @foreach ($field['options'] as $key => $value)
                <option value="{{ $key }}" @if(in_array($key, $field['value'])) selected @endif>{{ $value }}</option>
            @endforeach
        @endif
    </select>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif

@include('crud::fields.inc.wrapper_end')

{{-- Include Select2 CSS and JS --}}
@push('after_styles')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endpush

@push('after_scripts')
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script>
        // Initialize Select2 for multi-select dropdowns
        setTimeout(function() {
            $('#{{ $fieldId }}').select2({
                placeholder: '{{ $field['placeholder'] ?? 'Válassz...' }}',
                allowClear: true,
                width: '100%'
            });
        }, 100);
    </script>
@endpush
