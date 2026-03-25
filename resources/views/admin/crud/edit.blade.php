@extends('admin.layouts.app')

@section('title', $title ?? 'Szerkesztés')
@section('page-title', $pageTitle ?? 'Szerkesztés')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>{{ $pageTitle ?? 'Szerkesztés' }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route($updateRoute ?? 'admin.dashboard', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                @foreach($fields as $field)
                <div class="mb-3">
                    <label for="{{ $field['name'] }}" class="form-label">{{ $field['label'] ?? $field['name'] }}</label>
                    
                    @if($field['type'] === 'text' || $field['type'] === 'email' || $field['type'] === 'number')
                        <input type="{{ $field['type'] }}" 
                               class="form-control @error($field['name']) is-invalid @enderror" 
                               id="{{ $field['name'] }}" 
                               name="{{ $field['name'] }}" 
                               value="{{ old($field['name'], $item->{$field['name']} ?? '') }}"
                               @if(isset($field['required']) && $field['required']) required @endif>
                    
                    @elseif($field['type'] === 'textarea')
                        <textarea class="form-control @error($field['name']) is-invalid @enderror" 
                                  id="{{ $field['name'] }}" 
                                  name="{{ $field['name'] }}" 
                                  rows="{{ $field['rows'] ?? 5 }}"
                                  @if(isset($field['required']) && $field['required']) required @endif>{{ old($field['name'], $item->{$field['name']} ?? '') }}</textarea>
                    
                    @elseif($field['type'] === 'select')
                        <select class="form-select @error($field['name']) is-invalid @enderror" 
                                id="{{ $field['name'] }}" 
                                name="{{ $field['name'] }}"
                                @if(isset($field['required']) && $field['required']) required @endif>
                            <option value="">Válassz...</option>
                            @foreach($field['options'] ?? [] as $optionValue => $optionLabel)
                            <option value="{{ $optionValue }}" {{ old($field['name'], $item->{$field['name']} ?? '') == $optionValue ? 'selected' : '' }}>
                                {{ $optionLabel }}
                            </option>
                            @endforeach
                        </select>
                    
                    @elseif($field['type'] === 'select_multiple')
                        @php
                            $selected = old($field['name'], $field['selected'] ?? []);
                            if (is_object($item->{$field['name']} ?? null) && method_exists($item->{$field['name']}, 'pluck')) {
                                $selected = $item->{$field['name']}->pluck('id')->toArray();
                            }
                        @endphp
                        <select class="form-select @error($field['name']) is-invalid @enderror" 
                                id="{{ $field['name'] }}" 
                                name="{{ $field['name'] }}[]"
                                multiple
                                size="5"
                                @if(isset($field['required']) && $field['required']) required @endif>
                            @foreach($field['options'] ?? [] as $optionValue => $optionLabel)
                            <option value="{{ $optionValue }}" {{ in_array($optionValue, $selected) ? 'selected' : '' }}>
                                {{ $optionLabel }}
                            </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Több elem kiválasztásához tartsd lenyomva a Ctrl (Windows) vagy Cmd (Mac) billentyűt.</small>
                    
                    @elseif($field['type'] === 'checkbox')
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="{{ $field['name'] }}" 
                                   name="{{ $field['name'] }}" 
                                   value="1"
                                   {{ old($field['name'], $item->{$field['name']} ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="{{ $field['name'] }}">
                                {{ $field['checkbox_label'] ?? '' }}
                            </label>
                        </div>
                    
                    @elseif($field['type'] === 'color')
                        <input type="color" 
                               class="form-control form-control-color @error($field['name']) is-invalid @enderror" 
                               id="{{ $field['name'] }}" 
                               name="{{ $field['name'] }}" 
                               value="{{ old($field['name'], $item->{$field['name']} ?? '#000000') }}"
                               @if(isset($field['required']) && $field['required']) required @endif>
                    
                    @elseif($field['type'] === 'file')
                        <input type="file" 
                               class="form-control @error($field['name']) is-invalid @enderror" 
                               id="{{ $field['name'] }}" 
                               name="{{ $field['name'] }}"
                               @if(isset($field['accept'])) accept="{{ $field['accept'] }}" @endif>
                        @if(isset($item->{$field['name']}) && $item->{$field['name']})
                            <small class="text-muted">Jelenlegi fájl: {{ $item->{$field['name']} }}</small>
                        @endif
                    @endif
                    
                    @error($field['name'])
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @endforeach
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route($indexRoute ?? 'admin.dashboard') }}" class="btn btn-secondary">Mégse</a>
                    <button type="submit" class="btn btn-primary">Mentés</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

