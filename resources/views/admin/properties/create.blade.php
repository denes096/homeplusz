@extends('admin.layouts.app')

@section('title', $title)
@section('page-title', $pageTitle)

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    #image_preview {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 10px;
    }
    .image-preview-item {
        position: relative;
        width: 150px;
        height: 150px;
        border: 2px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
    }
    .image-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .image-preview-item .remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, 0.8);
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #map {
        height: 400px;
        width: 100%;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>{{ $pageTitle }}</h4>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" id="propertyTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="base-tab" data-bs-toggle="tab" data-bs-target="#base" type="button" role="tab">Alapadatok</button>
                </li>
                @foreach($attributesByCategory->keys() as $categoryName)
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="{{ Str::slug($categoryName) }}-tab" data-bs-toggle="tab" data-bs-target="#{{ Str::slug($categoryName) }}" type="button" role="tab">{{ $categoryName }}</button>
                </li>
                @endforeach
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">Dokumentumok</button>
                </li>
            </ul>

            <form action="{{ route($storeRoute) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="tab-content">
                    <!-- Base Tab -->
                    <div class="tab-pane fade show active" id="base" role="tabpanel">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Aktív</label>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1" {{ old('featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="featured">Kiemelt</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="title" class="form-label">Cím <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="price" class="form-label">Irányár (Ft) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Típus <span class="text-danger">*</span></label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="ad_type" id="ad_type_sell" value="sell" {{ old('ad_type', 'sell') === 'sell' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="ad_type_sell">Eladó</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="ad_type" id="ad_type_rent" value="rent" {{ old('ad_type') === 'rent' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="ad_type_rent">Kiadó</label>
                                    </div>
                                </div>
                                @error('ad_type')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="property_code" class="form-label">Ingatlan azonosító <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('property_code') is-invalid @enderror" id="property_code" name="property_code" value="{{ old('property_code', $defaultPropertyCode) }}" required>
                                @error('property_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="project_id" class="form-label">Projekt</label>
                                <select class="form-select @error('project_id') is-invalid @enderror" id="project_id" name="project_id">
                                    <option value="">Válassz...</option>
                                    @foreach($projects as $id => $name)
                                    <option value="{{ $id }}" {{ old('project_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="client_id" class="form-label">Megbízó</label>
                                <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id">
                                    <option value="">Válassz...</option>
                                    @foreach($clients as $id => $name)
                                    <option value="{{ $id }}" {{ old('client_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="user_id" class="form-label">Referens</label>
                                <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                                    <option value="">Válassz...</option>
                                    @foreach($users as $id => $name)
                                    <option value="{{ $id }}" {{ old('user_id', auth()->id()) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="settlement_id" class="form-label">Település <span class="text-danger">*</span></label>
                                <select class="form-select @error('settlement_id') is-invalid @enderror" id="settlement_id" name="settlement_id" required>
                                    <option value="">Válassz...</option>
                                    @foreach($settlements as $id => $name)
                                    <option value="{{ $id }}" {{ old('settlement_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('settlement_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="settlement_part_id" class="form-label">Településrész</label>
                                <select class="form-select @error('settlement_part_id') is-invalid @enderror" id="settlement_part_id" name="settlement_part_id">
                                    <option value="">Válassz...</option>
                                    @foreach($settlementParts as $part)
                                    <option value="{{ $part->id }}" 
                                            data-settlement="{{ $part->settlement_id }}"
                                            {{ old('settlement_part_id') == $part->id ? 'selected' : '' }}>
                                        {{ $part->settlement->fullName ?? '' }} - {{ $part->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('settlement_part_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="property_type_id" class="form-label">Ingatlantípus <span class="text-danger">*</span></label>
                                <select class="form-select @error('property_type_id') is-invalid @enderror" id="property_type_id" name="property_type_id" required>
                                    <option value="">Válassz...</option>
                                    @foreach($propertyTypes as $id => $name)
                                    <option value="{{ $id }}" {{ old('property_type_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('property_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="property_subtype_id" class="form-label">Ingatlan altípus</label>
                                <select class="form-select @error('property_subtype_id') is-invalid @enderror" id="property_subtype_id" name="property_subtype_id">
                                    <option value="">Válassz...</option>
                                    @foreach($propertySubtypes as $subtype)
                                    <option value="{{ $subtype->id }}" 
                                            data-property-type="{{ $subtype->property_type_id }}"
                                            {{ old('property_subtype_id') == $subtype->id ? 'selected' : '' }}>
                                        {{ $subtype->propertyType->name ?? '' }} - {{ $subtype->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('property_subtype_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="labels" class="form-label">Címkék</label>
                                <select class="form-select @error('labels') is-invalid @enderror" id="labels" name="labels[]" multiple>
                                    @foreach($labels as $id => $name)
                                    <option value="{{ $id }}" {{ in_array($id, old('labels', [])) ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Több címke kiválasztásához tartsd lenyomva a Ctrl (Windows) vagy Cmd (Mac) billentyűt</small>
                                @error('labels')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="images" class="form-label">Képek</label>
                                <input type="file" class="form-control @error('images') is-invalid @enderror" id="images" name="images[]" multiple accept="image/*">
                                <div id="image_preview"></div>
                                @error('images')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('images.*')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Részletes leírás</label>
                                <textarea class="form-control ckeditor @error('description') is-invalid @enderror" id="description" name="description" rows="10">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="inner_comments" class="form-label">Belső komment</label>
                                <textarea class="form-control ckeditor @error('inner_comments') is-invalid @enderror" id="inner_comments" name="inner_comments" rows="5">{{ old('inner_comments') }}</textarea>
                                @error('inner_comments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Teljes cím (térkép)</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" placeholder="Adja meg a teljes címet, amelyet a térkép megjelenítéshez használunk">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="latitude" class="form-label">Szélesség (Latitude)</label>
                                <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude') }}" placeholder="pl. 47.4979">
                                <small class="form-text text-muted">Automatikusan kitöltődik a cím alapján</small>
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="longitude" class="form-label">Hosszúság (Longitude)</label>
                                <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude') }}" placeholder="pl. 19.0402">
                                <small class="form-text text-muted">Automatikusan kitöltődik a cím alapján</small>
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 text-primary"><i class="fas fa-map-marked-alt me-2"></i>Térkép</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="map"></div>
                                    </div>
                                    <div class="card-footer bg-light">
                                        <div class="d-flex flex-wrap gap-2">
                                            <button type="button" id="get-current-location" class="btn btn-info btn-sm">
                                                <i class="fas fa-location-arrow me-1"></i>Jelenlegi helyzet
                                            </button>
                                            <button type="button" id="search-address" class="btn btn-primary btn-sm">
                                                <i class="fas fa-search me-1"></i>Cím keresése
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Attribute Tabs -->
                    @foreach($attributesByCategory as $categoryName => $categoryAttributes)
                    <div class="tab-pane fade" id="{{ Str::slug($categoryName) }}" role="tabpanel">
                        <div class="row">
                            @foreach($categoryAttributes as $attribute)
                            <div class="col-md-{{ $attribute->type === 'select_multiple' ? '12' : '6' }} col-lg-{{ $attribute->type === 'select_multiple' ? '12' : '4' }} mb-3">
                                @if($attribute->type === 'checkbox')
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="property_{{ $attribute->id }}" name="properties[{{ $attribute->id }}]" value="1" {{ old("properties.{$attribute->id}") ? 'checked' : '' }}>
                                        <label class="form-check-label" for="property_{{ $attribute->id }}">{{ $attribute->label }}</label>
                                    </div>
                                @elseif($attribute->type === 'radio')
                                    @php
                                        $values = json_decode($attribute->values, false);
                                        $values = array_combine($values, $values);
                                    @endphp
                                    <label class="form-label">{{ $attribute->label }}</label>
                                    <div>
                                        @foreach($values as $key => $value)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="property_{{ $attribute->id }}_{{ $key }}" name="properties[{{ $attribute->id }}]" value="{{ $value }}" {{ old("properties.{$attribute->id}") == $value ? 'checked' : '' }}>
                                            <label class="form-check-label" for="property_{{ $attribute->id }}_{{ $key }}">{{ $value }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                @elseif($attribute->type === 'number')
                                    <label for="property_{{ $attribute->id }}" class="form-label">
                                        {{ $attribute->label }}
                                        @if($attribute->prefix) <span class="text-muted">({{ $attribute->prefix }})</span> @endif
                                        @if($attribute->suffix) <span class="text-muted">({{ $attribute->suffix }})</span> @endif
                                    </label>
                                    <div class="input-group">
                                        @if($attribute->prefix)
                                        <span class="input-group-text">{{ $attribute->prefix }}</span>
                                        @endif
                                        <input type="number" class="form-control" id="property_{{ $attribute->id }}" name="properties[{{ $attribute->id }}]" value="{{ old("properties.{$attribute->id}") }}">
                                        @if($attribute->suffix)
                                        <span class="input-group-text">{{ $attribute->suffix }}</span>
                                        @endif
                                    </div>
                                @elseif($attribute->type === 'select')
                                    @php
                                        $values = (array) json_decode($attribute->values, true);
                                        if (isset($values[0]['id'])) {
                                            $values = array_combine(array_column($values, 'id'), array_column($values, 'label'));
                                        } else {
                                            $values = array_combine($values, $values);
                                        }
                                    @endphp
                                    <label for="property_{{ $attribute->id }}" class="form-label">{{ $attribute->label }}</label>
                                    <select class="form-select" id="property_{{ $attribute->id }}" name="properties[{{ $attribute->id }}]">
                                        <option value="">Válassz...</option>
                                        @foreach($values as $key => $value)
                                        <option value="{{ $key }}" {{ old("properties.{$attribute->id}") == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                @elseif($attribute->type === 'select_multiple')
                                    @php
                                        $values = (array) json_decode($attribute->values, true);
                                    @endphp
                                    <label for="property_{{ $attribute->id }}" class="form-label">{{ $attribute->label }}</label>
                                    <select class="form-select" id="property_{{ $attribute->id }}" name="properties[{{ $attribute->id }}][]" multiple>
                                        @foreach($values as $value)
                                        <option value="{{ $value }}" {{ in_array($value, old("properties.{$attribute->id}", [])) ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Több érték kiválasztásához tartsd lenyomva a Ctrl (Windows) vagy Cmd (Mac) billentyűt</small>
                                @else
                                    <label for="property_{{ $attribute->id }}" class="form-label">
                                        {{ $attribute->label }}
                                        @if($attribute->prefix) <span class="text-muted">({{ $attribute->prefix }})</span> @endif
                                        @if($attribute->suffix) <span class="text-muted">({{ $attribute->suffix }})</span> @endif
                                    </label>
                                    <div class="input-group">
                                        @if($attribute->prefix)
                                        <span class="input-group-text">{{ $attribute->prefix }}</span>
                                        @endif
                                        <input type="text" class="form-control" id="property_{{ $attribute->id }}" name="properties[{{ $attribute->id }}]" value="{{ old("properties.{$attribute->id}") }}">
                                        @if($attribute->suffix)
                                        <span class="input-group-text">{{ $attribute->suffix }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                    <!-- Documents Tab -->
                    <div class="tab-pane fade" id="documents" role="tabpanel">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Megjegyzés:</strong> A dokumentumok feltöltése csak az ingatlan létrehozása után lesz elérhető. Először mentsd el az ingatlant, majd szerkeszd újra a dokumentumok hozzáadásához.
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route($indexRoute) }}" class="btn btn-secondary">Mégse</a>
                    <button type="submit" class="btn btn-primary">Mentés</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize CKEditor
    if (typeof CKEDITOR !== 'undefined') {
        document.querySelectorAll('.ckeditor').forEach(function(el) {
            CKEDITOR.replace(el, {
                height: 300,
                removePlugins: 'elementspath',
                resize_enabled: true
            });
        });
    }

    // Initialize Select2 for labels
    $('#labels').select2({
        placeholder: 'Válassz címkéket...',
        allowClear: true
    });

    // Project code generation
    const projectSelect = document.getElementById('project_id');
    const codeInput = document.getElementById('property_code');
    
    if (projectSelect && codeInput) {
        projectSelect.addEventListener('change', function() {
            const projectId = this.value;
            if (projectId) {
                fetch(`/projekt/getNextPropertyId/${projectId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.code) {
                            codeInput.value = data.code;
                        }
                    })
                    .catch(error => {
                        console.error('Hiba történt:', error);
                    });
            }
        });
    }

    // Settlement part filtering
    const settlementSelect = document.getElementById('settlement_id');
    const settlementPartSelect = document.getElementById('settlement_part_id');
    
    if (settlementSelect && settlementPartSelect) {
        settlementSelect.addEventListener('change', function() {
            const settlementId = this.value;
            Array.from(settlementPartSelect.options).forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block';
                } else {
                    const optionSettlement = option.getAttribute('data-settlement');
                    option.style.display = (optionSettlement === settlementId) ? 'block' : 'none';
                }
            });
            settlementPartSelect.value = '';
        });
    }

    // Property subtype filtering
    const propertyTypeSelect = document.getElementById('property_type_id');
    const propertySubtypeSelect = document.getElementById('property_subtype_id');
    
    if (propertyTypeSelect && propertySubtypeSelect) {
        propertyTypeSelect.addEventListener('change', function() {
            const propertyTypeId = this.value;
            Array.from(propertySubtypeSelect.options).forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block';
                } else {
                    const optionPropertyType = option.getAttribute('data-property-type');
                    option.style.display = (optionPropertyType === propertyTypeId) ? 'block' : 'none';
                }
            });
            propertySubtypeSelect.value = '';
        });
    }

    // Image preview
    const imageInput = document.getElementById('images');
    const imagePreview = document.getElementById('image_preview');
    let imageFiles = [];

    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            files.forEach(file => {
                if (file.type.startsWith('image/')) {
                    imageFiles.push(file);
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'image-preview-item';
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="Preview">
                            <button type="button" class="remove-btn" data-filename="${file.name}">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        imagePreview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        // Remove image from preview
        imagePreview.addEventListener('click', function(e) {
            if (e.target.closest('.remove-btn')) {
                const btn = e.target.closest('.remove-btn');
                const filename = btn.getAttribute('data-filename');
                imageFiles = imageFiles.filter(f => f.name !== filename);
                btn.closest('.image-preview-item').remove();
                
                // Update file input
                const dt = new DataTransfer();
                imageFiles.forEach(file => dt.items.add(file));
                imageInput.files = dt.files;
            }
        });
    }

    // Leaflet Map
    let map, marker;
    
    function initMap() {
        const lat = parseFloat(document.getElementById('latitude').value) || 47.4979;
        const lng = parseFloat(document.getElementById('longitude').value) || 19.0402;
        const defaultLocation = [lat, lng];

        map = L.map('map').setView(defaultLocation, 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        marker = L.marker(defaultLocation, { draggable: true }).addTo(map);

        marker.on('dragend', function(e) {
            const position = e.target.getLatLng();
            document.getElementById('latitude').value = position.lat.toFixed(8);
            document.getElementById('longitude').value = position.lng.toFixed(8);

            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.lat}&lon=${position.lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data.display_name) {
                        document.getElementById('address').value = data.display_name;
                    }
                })
                .catch(error => console.log('Geocoding error:', error));
        });

        document.getElementById('search-address').addEventListener('click', function() {
            const address = document.getElementById('address').value;
            if (address) {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            const location = [parseFloat(data[0].lat), parseFloat(data[0].lon)];
                            map.setView(location, 15);
                            marker.setLatLng(location);
                            document.getElementById('latitude').value = data[0].lat;
                            document.getElementById('longitude').value = data[0].lon;
                        } else {
                            alert('A cím nem található');
                        }
                    })
                    .catch(error => {
                        console.log('Geocoding error:', error);
                        alert('Hiba történt a cím keresése során');
                    });
            }
        });

        document.getElementById('get-current-location').addEventListener('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const pos = [position.coords.latitude, position.coords.longitude];
                    map.setView(pos, 15);
                    marker.setLatLng(pos);
                    document.getElementById('latitude').value = position.coords.latitude.toFixed(8);
                    document.getElementById('longitude').value = position.coords.longitude.toFixed(8);

                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.coords.latitude}&lon=${position.coords.longitude}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.display_name) {
                                document.getElementById('address').value = data.display_name;
                            }
                        })
                        .catch(error => console.log('Geocoding error:', error));
                });
            } else {
                alert('A böngésző nem támogatja a geolokációt.');
            }
        });
    }

    initMap();
});
</script>
@endpush
@endsection

