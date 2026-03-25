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
                <a href="{{ route($editRoute, $client->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Szerkesztés
                </a>
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="clientTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="data-tab" data-bs-toggle="tab" data-bs-target="#data" type="button" role="tab">Alapadatok</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contacts-tab" data-bs-toggle="tab" data-bs-target="#contacts" type="button" role="tab">Kapcsolattartók</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">Dokumentumok</button>
                </li>
            </ul>

            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="data" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-3">Név:</dt>
                                <dd class="col-sm-9">{{ $client->name }}</dd>
                                
                                <dt class="col-sm-3">Cég:</dt>
                                <dd class="col-sm-9">{{ $client->company ?: '-' }}</dd>
                                
                                <dt class="col-sm-3">Email:</dt>
                                <dd class="col-sm-9">{{ $client->email ?: '-' }}</dd>
                                
                                <dt class="col-sm-3">Telefon:</dt>
                                <dd class="col-sm-9">{{ $client->phone ?: '-' }}</dd>
                                
                                <dt class="col-sm-3">Mobil:</dt>
                                <dd class="col-sm-9">{{ $client->mobile ?: '-' }}</dd>
                                
                                @if($client->user)
                                <dt class="col-sm-3">Referens:</dt>
                                <dd class="col-sm-9">{{ $client->user->name }}</dd>
                                @endif
                                
                                <dt class="col-sm-3">Cím:</dt>
                                <dd class="col-sm-9">{{ $client->address ?: '-' }}</dd>
                                
                                <dt class="col-sm-3">Város:</dt>
                                <dd class="col-sm-9">{{ $client->city ?: '-' }}</dd>
                                
                                <dt class="col-sm-3">Irányítószám:</dt>
                                <dd class="col-sm-9">{{ $client->zip_code ?: '-' }}</dd>
                                
                                <dt class="col-sm-3">Ország:</dt>
                                <dd class="col-sm-9">{{ $client->country ?: '-' }}</dd>
                                
                                <dt class="col-sm-3">Státusz:</dt>
                                <dd class="col-sm-9">
                                    <span class="badge bg-{{ $client->status === 'Aktív' ? 'success' : ($client->status === 'Felfüggesztve' ? 'warning' : 'secondary') }}">
                                        {{ $client->status }}
                                    </span>
                                </dd>
                                
                                <dt class="col-sm-3">Megjegyzések:</dt>
                                <dd class="col-sm-9">{{ $client->notes ?: '-' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="contacts" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-body">
                            @include('admin.clients._contacts_list', ['contacts' => $client->contacts])
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="documents" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-body">
                            @include('admin.clients._documents_list', ['documents' => $client->documents])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

