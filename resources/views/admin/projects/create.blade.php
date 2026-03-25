@extends('admin.layouts.app')

@section('title', $title)
@section('page-title', $pageTitle)

@push('styles')
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
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>{{ $pageTitle }}</h4>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" id="projectTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">Alapadatok</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="storage-tab" data-bs-toggle="tab" data-bs-target="#storage" type="button" role="tab">Tárolók</button>
                </li>
            </ul>

            <form action="{{ route($storeRoute) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="basic" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="project_code" class="form-label">Projekt azonosító <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('project_code') is-invalid @enderror" id="project_code" name="project_code" value="{{ old('project_code', $nextCode) }}" required>
                                @error('project_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Név <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="title" class="form-label">Összefoglaló <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Leírás <span class="text-danger">*</span></label>
                                <textarea class="form-control ckeditor @error('description') is-invalid @enderror" id="description" name="description" rows="10" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="images" class="form-label">Képek</label>
                                <input type="file" class="form-control @error('images') is-invalid @enderror" id="images" name="images[]" multiple accept="image/*">
                                @error('images')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="image_preview"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="partner_id" class="form-label">Partner</label>
                                <select class="form-select @error('partner_id') is-invalid @enderror" id="partner_id" name="partner_id">
                                    <option value="">Válassz...</option>
                                    @foreach($partners as $id => $name)
                                    <option value="{{ $id }}" {{ old('partner_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('partner_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="user_id" class="form-label">Referens</label>
                                <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                                    <option value="">Válassz...</option>
                                    @foreach($users as $id => $name)
                                    <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="storage" role="tabpanel">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="storage_count" class="form-label">Tárolók száma <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('storage_count') is-invalid @enderror" id="storage_count" name="storage_count" value="{{ old('storage_count', 0) }}" min="0" required>
                                @error('storage_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="storage_type" class="form-label">Tároló típusa <span class="text-danger">*</span></label>
                                <select class="form-select @error('storage_type') is-invalid @enderror" id="storage_type" name="storage_type" required>
                                    <option value="optional" {{ old('storage_type', 'optional') == 'optional' ? 'selected' : '' }}>Bármelyik választható</option>
                                    <option value="fixed" {{ old('storage_type') == 'fixed' ? 'selected' : '' }}>Fixen hozzárendelt</option>
                                </select>
                                @error('storage_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="is_required_storage" name="is_required_storage" value="1" {{ old('is_required_storage') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_required_storage">Kötelező megvásárolni</label>
                                </div>
                                @error('is_required_storage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
});
</script>
@endpush
@endsection

