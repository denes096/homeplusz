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
                <a href="{{ route($editRoute, $customer->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Szerkesztés
                </a>
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="customerTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="data-tab" data-bs-toggle="tab" data-bs-target="#data" type="button" role="tab">Alapadatok</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contacts-tab" data-bs-toggle="tab" data-bs-target="#contacts" type="button" role="tab">Kapcsolattartók</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">Dokumentumok</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="searches-tab" data-bs-toggle="tab" data-bs-target="#searches" type="button" role="tab">Keresési paraméterek</button>
                </li>
            </ul>

            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="data" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-3">Név:</dt>
                                <dd class="col-sm-9">{{ $customer->name_0 }}</dd>
                                
                                <dt class="col-sm-3">Email:</dt>
                                <dd class="col-sm-9">{{ $customer->email ?: '-' }}</dd>
                                
                                <dt class="col-sm-3">Telefon:</dt>
                                <dd class="col-sm-9">{{ $customer->phone_0 ?: '-' }}</dd>
                                
                                @if($customer->referens)
                                <dt class="col-sm-3">Referens:</dt>
                                <dd class="col-sm-9">{{ $customer->referens->name }}</dd>
                                @endif
                                
                                <dt class="col-sm-3">Státusz:</dt>
                                <dd class="col-sm-9">
                                    <span class="badge bg-{{ $customer->status === 'Aktív' ? 'success' : ($customer->status === 'Felfüggesztve' ? 'warning' : 'secondary') }}">
                                        {{ $customer->status }}
                                    </span>
                                </dd>
                                
                                <dt class="col-sm-3">Kategória:</dt>
                                <dd class="col-sm-9">{{ $customer->kategoria === 'maganszemely' ? 'Magánszemély' : 'Beruházó' }}</dd>
                                
                                <dt class="col-sm-3">Cím:</dt>
                                <dd class="col-sm-9">{{ $customer->address ?: '-' }}</dd>
                                
                                <dt class="col-sm-3">Megjegyzés:</dt>
                                <dd class="col-sm-9">{{ $customer->note ?: '-' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="contacts" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-body">
                            @include('admin.customers._contacts_list', ['contacts' => $customer->contacts])
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="documents" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-body">
                            @include('admin.customers._documents_list', ['documents' => $customer->documents])
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="searches" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-body">
                            @if($searches->isEmpty())
                                <div class="alert alert-info">Nincsenek mentett keresési paraméterek.</div>
                            @else
                                @foreach($searches as $s)
                                    <div class="mb-3 border p-3 rounded">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div><strong>Keresés #{{ $s->id }}</strong></div>
                                            <div>
                                                <button class="btn btn-sm btn-primary execute-search" data-id="{{ $s->id }}">Futtatás</button>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <pre class="bg-light p-2 rounded" style="white-space:pre-wrap; font-size: 0.9em;">{{ json_encode($s->search_array, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                        <div id="search-result-{{ $s->id }}"></div>
                                        <div id="offers-{{ $s->id }}">
                                            @include('admin.customers._offers_list', ['offers' => \App\Models\CustomerOffer::where('customer_id', $customer->id)->where('customer_search_id', $s->id)->with('customer')->orderBy('sent_at', 'desc')->get()])
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const customerId = {{ $customer->id }};
    
    // Execute search functionality
    document.querySelectorAll('.execute-search').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const searchId = this.dataset.id;
            const resultDiv = document.getElementById('search-result-' + searchId);
            
            resultDiv.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Betöltés...</span></div></div>';
            
            fetch(`/admin/customers/${customerId}/execute-search/${searchId}`)
                .then(response => response.text())
                .then(html => {
                    resultDiv.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    resultDiv.innerHTML = '<div class="alert alert-danger">Hiba történt a keresés futtatása során.</div>';
                });
        });
    });
});
</script>
@endpush
@endsection

