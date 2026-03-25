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
                <a href="{{ route($editRoute, $project->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Szerkesztés
                </a>
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="projectTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="data-tab" data-bs-toggle="tab" data-bs-target="#data" type="button" role="tab">Alapadatok</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">Dokumentumok</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="properties-tab" data-bs-toggle="tab" data-bs-target="#properties" type="button" role="tab">Ingatlanok</button>
                </li>
            </ul>

            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="data" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-3">Projekt azonosító:</dt>
                                <dd class="col-sm-9">{{ $project->project_code }}</dd>
                                
                                <dt class="col-sm-3">Név:</dt>
                                <dd class="col-sm-9">{{ $project->name }}</dd>
                                
                                <dt class="col-sm-3">Összefoglaló:</dt>
                                <dd class="col-sm-9">{{ $project->title }}</dd>
                                
                                <dt class="col-sm-3">Leírás:</dt>
                                <dd class="col-sm-9">{!! $project->description !!}</dd>
                                
                                @if($project->user)
                                <dt class="col-sm-3">Referens:</dt>
                                <dd class="col-sm-9">{{ $project->user->name }}</dd>
                                @endif
                                
                                @if($project->partner)
                                <dt class="col-sm-3">Partner:</dt>
                                <dd class="col-sm-9">{{ $project->partner->name }}</dd>
                                @endif
                                
                                <dt class="col-sm-3">Tárolók száma:</dt>
                                <dd class="col-sm-9">{{ $project->storage_count }}</dd>
                                
                                <dt class="col-sm-3">Tároló típusa:</dt>
                                <dd class="col-sm-9">{{ $project->storage_type === 'fixed' ? 'Fixen hozzárendelt' : 'Bármelyik választható' }}</dd>
                                
                                <dt class="col-sm-3">Kötelező megvásárolni:</dt>
                                <dd class="col-sm-9">
                                    <span class="badge bg-{{ $project->is_required_storage ? 'success' : 'secondary' }}">
                                        {{ $project->is_required_storage ? 'Igen' : 'Nem' }}
                                    </span>
                                </dd>
                                
                                @if(!empty($project->images))
                                    @php
                                        $images = json_decode($project->images, true) ?: [];
                                    @endphp
                                    @if(!empty($images))
                                    <dt class="col-sm-3">Képek:</dt>
                                    <dd class="col-sm-9">
                                        <div class="d-flex gap-2 flex-wrap">
                                            @foreach($images as $image)
                                                @if(Storage::disk('public')->exists($image))
                                                <img src="{{ Storage::url($image) }}" alt="Project image" style="max-width: 200px; max-height: 200px; object-fit: cover; border-radius: 8px;">
                                                @endif
                                            @endforeach
                                        </div>
                                    </dd>
                                    @endif
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="documents" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-body">
                            @include('admin.projects._documents_list', ['documents' => $documents])
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="properties" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-body">
                            @if($project->properties->isEmpty())
                                <div class="alert alert-info">Nincsenek hozzárendelt ingatlanok.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Ingatlan kód</th>
                                                <th>Cím</th>
                                                <th>Település</th>
                                                <th>Ár</th>
                                                <th>Műveletek</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($project->properties as $property)
                                            <tr>
                                                <td>{{ $property->property_code ?? $property->id }}</td>
                                                <td>{{ $property->title }}</td>
                                                <td>{{ $property->settlement->name ?? '-' }}</td>
                                                <td>{{ number_format($property->price, 0, ',', ' ') }} Ft</td>
                                                <td>
                                                    <a href="{{ route('admin.properties.show', $property->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i> Megtekintés
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

