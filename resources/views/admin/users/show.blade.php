@extends('admin.layouts.app')

@section('title', $title)
@section('page-title', $pageTitle)

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>{{ $pageTitle }}</h4>
            <div class="action-buttons">
                <a href="{{ route($indexRoute) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Vissza
                </a>
                <a href="{{ route($editRoute, $user->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Szerkesztés
                </a>
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="userTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="data-tab" data-bs-toggle="tab" data-bs-target="#data" type="button" role="tab">Alapadatok</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab">Szerepkörök és jogosultságok</button>
                </li>
            </ul>

            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="data" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="row">
                                @if($user->profile_picture)
                                    <div class="col-md-12 mb-3">
                                        <img src="{{ Storage::url($user->profile_picture) }}" alt="Profilkép" style="max-width: 200px; max-height: 200px; object-fit: cover;" class="rounded">
                                    </div>
                                @endif
                                
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Név:</dt>
                                        <dd class="col-sm-8">{{ $user->name }}</dd>
                                        
                                        <dt class="col-sm-4">Email:</dt>
                                        <dd class="col-sm-8">{{ $user->email }}</dd>
                                        
                                        <dt class="col-sm-4">Pozíció:</dt>
                                        <dd class="col-sm-8">{{ $user->position ?: '-' }}</dd>
                                        
                                        <dt class="col-sm-4">Létrehozva:</dt>
                                        <dd class="col-sm-8">{{ $user->created_at->format('Y-m-d H:i:s') }}</dd>
                                        
                                        <dt class="col-sm-4">Frissítve:</dt>
                                        <dd class="col-sm-8">{{ $user->updated_at->format('Y-m-d H:i:s') }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="roles" role="tabpanel">
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Szerepkörök</h5>
                                </div>
                                <div class="card-body">
                                    @if($user->roles->count() > 0)
                                        <ul class="list-unstyled">
                                            @foreach($user->roles as $role)
                                                <li class="mb-2">
                                                    <span class="badge bg-primary">{{ $role->name }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted">Nincs szerepkör hozzárendelve.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Közvetlen jogosultságok</h5>
                                </div>
                                <div class="card-body">
                                    @if($user->permissions->count() > 0)
                                        <ul class="list-unstyled">
                                            @foreach($user->permissions as $permission)
                                                <li class="mb-2">
                                                    <span class="badge bg-success">{{ $permission->name }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted">Nincs közvetlen jogosultság hozzárendelve.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

