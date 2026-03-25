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
                <a href="{{ route($editRoute, $search->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Szerkesztés
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">ID:</dt>
                        <dd class="col-sm-8">{{ $search->id }}</dd>
                        
                        <dt class="col-sm-4">Vevő:</dt>
                        <dd class="col-sm-8">
                            <a href="{{ route('admin.customers.show', $search->customer_id) }}">
                                {{ $search->customer->name_0 ?? 'N/A' }}
                            </a>
                        </dd>
                    </dl>
                </div>
            </div>
            
            <div class="mt-4">
                <h5>Keresési paraméterek</h5>
                <div class="card">
                    <div class="card-body">
                        <pre class="bg-light p-3 rounded" style="white-space:pre-wrap; font-size: 0.9em; max-height: 500px; overflow-y: auto;">{{ json_encode($searchArray, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.customers.show', $search->customer_id) }}" class="btn btn-info">
                        <i class="fas fa-user me-2"></i>Vevő megtekintése
                    </a>
                    <button class="btn btn-primary execute-search" 
                            data-customer-id="{{ $search->customer_id }}" 
                            data-search-id="{{ $search->id }}">
                        <i class="fas fa-play me-2"></i>Keresés futtatása
                    </button>
                    <button class="btn btn-success view-offers" 
                            data-customer-id="{{ $search->customer_id }}" 
                            data-search-id="{{ $search->id }}">
                        <i class="fas fa-envelope me-2"></i>Kiküldött ajánlatok
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search Results Modal -->
<div class="modal fade" id="searchResultsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Keresési eredmények</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="searchResultsContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Betöltés...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezárás</button>
            </div>
        </div>
    </div>
</div>

<!-- Offers Modal -->
<div class="modal fade" id="offersModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kiküldött ajánlatok</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="offersContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Betöltés...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezárás</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Handle search execution
    document.querySelectorAll('.execute-search').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var customerId = this.dataset.customerId;
            var searchId = this.dataset.searchId;
            
            var modal = new bootstrap.Modal(document.getElementById('searchResultsModal'));
            modal.show();
            
            fetch('{{ url('admin') }}/customers/' + customerId + '/execute-search/' + searchId)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('searchResultsContent').innerHTML = html;
                    addSingleOfferListeners(customerId, searchId);
                })
                .catch(error => {
                    document.getElementById('searchResultsContent').innerHTML = 
                        '<div class="alert alert-danger">Hiba történt a keresés futtatása során.</div>';
                });
        });
    });
    
    // Handle view offers button
    document.querySelectorAll('.view-offers').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var customerId = this.dataset.customerId;
            var searchId = this.dataset.searchId;
            
            var modal = new bootstrap.Modal(document.getElementById('offersModal'));
            modal.show();
            
            fetch('{{ url('admin') }}/customers/' + customerId + '/offers/' + searchId)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('offersContent').innerHTML = html;
                })
                .catch(error => {
                    document.getElementById('offersContent').innerHTML = 
                        '<div class="alert alert-danger">Hiba történt az ajánlatok betöltése során.</div>';
                });
        });
    });
    
    function addSingleOfferListeners(customerId, searchId) {
        document.querySelectorAll('.send-single-offer').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var propertyId = this.dataset.propertyId;
                
                if (confirm('Biztosan szeretnéd elküldeni ezt az ingatlant ajánlatként?')) {
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Küldés...';
                    
                    fetch('{{ url('admin') }}/customers/' + customerId + '/send-offer', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            search_id: searchId,
                            property_ids: [propertyId]
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.innerHTML = '<i class="fas fa-check"></i> Elküldve';
                            this.classList.remove('btn-success');
                            this.classList.add('btn-outline-success');
                        } else {
                            alert('Hiba történt az ajánlat küldése során: ' + (data.message || 'Ismeretlen hiba'));
                            this.disabled = false;
                            this.innerHTML = 'Kiajánl';
                        }
                    })
                    .catch(error => {
                        alert('Hiba történt az ajánlat küldése során.');
                        this.disabled = false;
                        this.innerHTML = 'Kiajánl';
                    });
                }
            });
        });
    }
});
</script>
@endpush
@endsection

