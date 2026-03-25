@extends('admin.layouts.app')

@section('title', $title)
@section('page-title', $pageTitle)

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>{{ $pageTitle }}</h4>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" id="userTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">Alapadatok</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab">Szerepkörök és jogosultságok</button>
                </li>
            </ul>

            <form action="{{ route($updateRoute, $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="basic" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Név <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Jelszó</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                <small class="form-text text-muted">Hagyd üresen, ha nem szeretnéd megváltoztatni.</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Jelszó megerősítése</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="position" class="form-label">Pozíció</label>
                                <input type="text" class="form-control @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position', $user->position) }}">
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="profile_picture" class="form-label">Profilkép</label>
                                @if($user->profile_picture)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($user->profile_picture) }}" alt="Profilkép" style="max-width: 100px; max-height: 100px; object-fit: cover;" class="rounded">
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('profile_picture') is-invalid @enderror" id="profile_picture" name="profile_picture" accept="image/*">
                                @error('profile_picture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="image_preview" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="roles" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <h5>Szerepkörök</h5>
                                <div class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                                    @foreach($roles as $role)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input role-checkbox" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}" {{ (old('roles') && in_array($role->id, old('roles'))) || (!old('roles') && $user->roles->pluck('id')->contains($role->id)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="role_{{ $role->id }}">
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h5>Közvetlen jogosultságok</h5>
                                <p class="text-muted small">Ezek a jogosultságok közvetlenül a felhasználóhoz lesznek rendelve, a szerepkörökön kívül.</p>
                                <div class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                                    @foreach($groupedPermissions as $group => $perms)
                                        <div class="mb-3">
                                            <strong class="text-capitalize">{{ $group }}</strong>
                                            @foreach($perms as $permission)
                                                <div class="form-check ms-3">
                                                    <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}" {{ (old('permissions') && in_array($permission->id, old('permissions'))) || (!old('permissions') && $user->permissions->pluck('id')->contains($permission->id)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Mentés
                    </button>
                    <a href="{{ route($indexRoute) }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Mégse
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Image preview
    document.getElementById('profile_picture').addEventListener('change', function(e) {
        const preview = document.getElementById('image_preview');
        preview.innerHTML = '';
        
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '200px';
                img.style.maxHeight = '200px';
                img.style.objectFit = 'cover';
                img.className = 'rounded';
                preview.appendChild(img);
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@endpush
@endsection

