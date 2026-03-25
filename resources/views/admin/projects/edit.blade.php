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
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">Dokumentumok</button>
                </li>
            </ul>

            <form action="{{ route($updateRoute, $project->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="basic" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="project_code" class="form-label">Projekt azonosító <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('project_code') is-invalid @enderror" id="project_code" name="project_code" value="{{ old('project_code', $project->project_code) }}" required>
                                @error('project_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Név <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $project->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="title" class="form-label">Összefoglaló <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $project->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Leírás <span class="text-danger">*</span></label>
                                <textarea class="form-control ckeditor @error('description') is-invalid @enderror" id="description" name="description" rows="10" required>{{ old('description', $project->description) }}</textarea>
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
                                <div id="image_preview">
                                    @if(!empty($images))
                                        @foreach($images as $image)
                                            @if(Storage::disk('public')->exists($image))
                                            <div class="image-preview-item">
                                                <img src="{{ Storage::url($image) }}" alt="Preview">
                                                <button type="button" class="remove-btn" data-image="{{ $image }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                <input type="hidden" name="existing_images" id="existing_images" value="{{ json_encode($images ?? []) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="partner_id" class="form-label">Partner</label>
                                <select class="form-select @error('partner_id') is-invalid @enderror" id="partner_id" name="partner_id">
                                    <option value="">Válassz...</option>
                                    @foreach($partners as $id => $name)
                                    <option value="{{ $id }}" {{ old('partner_id', $project->partner_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
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
                                    <option value="{{ $id }}" {{ old('user_id', $project->user_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
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
                                <input type="number" class="form-control @error('storage_count') is-invalid @enderror" id="storage_count" name="storage_count" value="{{ old('storage_count', $project->storage_count) }}" min="0" required>
                                @error('storage_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="storage_type" class="form-label">Tároló típusa <span class="text-danger">*</span></label>
                                <select class="form-select @error('storage_type') is-invalid @enderror" id="storage_type" name="storage_type" required>
                                    <option value="optional" {{ old('storage_type', $project->storage_type) == 'optional' ? 'selected' : '' }}>Bármelyik választható</option>
                                    <option value="fixed" {{ old('storage_type', $project->storage_type) == 'fixed' ? 'selected' : '' }}>Fixen hozzárendelt</option>
                                </select>
                                @error('storage_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="is_required_storage" name="is_required_storage" value="1" {{ old('is_required_storage', $project->is_required_storage) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_required_storage">Kötelező megvásárolni</label>
                                </div>
                                @error('is_required_storage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="documents" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Dokumentumok kezelése</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-3 mb-2">
                                            <label for="document-name" class="form-label">Dokumentum neve</label>
                                            <input type="text" class="form-control" id="document-name" placeholder="Ha üres, a fájl neve lesz használva">
                                        </div>
                                        <div class="col-sm-6 col-md-3 mb-2">
                                            <label for="document-category" class="form-label">Kategória</label>
                                            <select class="form-select" id="document-category">
                                                <option value="">Válassz kategóriát</option>
                                                <option value="blueprint">Alaprajz</option>
                                                <option value="contract">Megbízás</option>
                                                <option value="foundation">TH alapító okirat</option>
                                                <option value="permit">Engedélyek</option>
                                                <option value="invoice">Számlák</option>
                                                <option value="other">Egyéb</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 col-md-4 mb-2">
                                            <label for="document-file" class="form-label">Fájl <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" id="document-file" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">
                                        </div>
                                        <div class="col-sm-6 col-md-2 mb-2">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" id="upload-document-btn" class="btn btn-primary d-block w-100">Feltöltés</button>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <label for="document-description" class="form-label">Leírás</label>
                                            <textarea class="form-control" id="document-description" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div id="documents-list">
                                    @include('admin.projects._documents_list', ['documents' => $project->documents ?? collect()])
                                </div>
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
    const projectId = {{ $project->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
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
    const existingImagesInput = document.getElementById('existing_images');
    let imageFiles = [];
    let existingImages = JSON.parse(existingImagesInput.value || '[]');

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
                const imagePath = btn.getAttribute('data-image');
                const filename = btn.getAttribute('data-filename');
                
                if (imagePath) {
                    // Remove existing image
                    existingImages = existingImages.filter(img => img !== imagePath);
                    existingImagesInput.value = JSON.stringify(existingImages);
                } else if (filename) {
                    // Remove new image
                    imageFiles = imageFiles.filter(f => f.name !== filename);
                    const dt = new DataTransfer();
                    imageFiles.forEach(file => dt.items.add(file));
                    imageInput.files = dt.files;
                }
                
                btn.closest('.image-preview-item').remove();
            }
        });
    }

    // Upload document functionality
    const uploadDocumentBtn = document.getElementById('upload-document-btn');
    if (uploadDocumentBtn) {
        uploadDocumentBtn.addEventListener('click', function() {
            const file = document.getElementById('document-file').files[0];
            if (!file) {
                alert('Kérjük, válasszon ki egy fájlt a feltöltéshez!');
                return;
            }

            const formData = new FormData();
            formData.append('name', document.getElementById('document-name').value || file.name);
            formData.append('category', document.getElementById('document-category').value || 'other');
            formData.append('file', file);
            formData.append('description', document.getElementById('document-description').value);
            formData.append('_token', csrfToken);

            uploadDocumentBtn.disabled = true;
            uploadDocumentBtn.textContent = 'Feltöltés...';

            fetch(`/admin/projects/${projectId}/upload-document`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('documents-list').innerHTML = data.documents_html;
                    document.getElementById('document-name').value = '';
                    document.getElementById('document-category').value = '';
                    document.getElementById('document-file').value = '';
                    document.getElementById('document-description').value = '';
                } else {
                    alert('Hiba: ' + (data.message || 'Ismeretlen hiba'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hiba történt a feltöltés során');
            })
            .finally(() => {
                uploadDocumentBtn.disabled = false;
                uploadDocumentBtn.textContent = 'Feltöltés';
            });
        });
    }

    // Delete document functionality
    window.deleteDocument = function(documentId) {
        if (!confirm('Biztosan törölni szeretné ezt a dokumentumot?')) {
            return;
        }

        fetch(`/admin/projects/document/${documentId}/delete`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('documents-list').innerHTML = data.documents_html;
            } else {
                alert('Hiba: ' + (data.message || 'Ismeretlen hiba'));
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            alert('Hiba történt a törlés során');
        });
    };
});
</script>
@endpush
@endsection

