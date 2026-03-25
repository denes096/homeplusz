@extends('admin.layouts.app')

@section('title', $title)
@section('page-title', $pageTitle)

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>{{ $pageTitle }}</h4>
            <a href="{{ route($createRoute) }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Új keresés
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Vevő</th>
                            <th>Műveletek</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($searches as $search)
                            <tr>
                                <td>{{ $search->id }}</td>
                                <td>{{ $search->customer->name_0 ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('admin.customers.show', $search->customer_id) }}" 
                                       class="btn btn-sm btn-info" title="Vevő megtekintése">
                                        <i class="fas fa-user"></i>
                                    </a>
                                    <a href="{{ route($showRoute, $search->id) }}" 
                                       class="btn btn-sm btn-secondary" title="Részletek">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-sm btn-primary execute-search" 
                                            data-customer-id="{{ $search->customer_id }}" 
                                            data-search-id="{{ $search->id }}"
                                            title="Keresés futtatása">
                                        <i class="fas fa-play"></i> Futtatás
                                    </button>
                                    <button class="btn btn-sm btn-success view-offers" 
                                            data-customer-id="{{ $search->customer_id }}" 
                                            data-search-id="{{ $search->id }}"
                                            title="Kiküldött ajánlatok megtekintése">
                                        <i class="fas fa-envelope"></i> Ajánlatok
                                    </button>
                                    <a href="{{ route($editRoute, $search->id) }}" 
                                       class="btn btn-sm btn-warning" title="Szerkesztés">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route($destroyRoute, $search->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Biztosan törölni szeretné ezt a keresést?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Törlés">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">
                                    <div class="alert alert-info">Nincsenek mentett keresések.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $searches->links() }}
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
            
            // Show modal
            var modal = new bootstrap.Modal(document.getElementById('searchResultsModal'));
            modal.show();
            
            // Load search results
            fetch('{{ url('admin') }}/customers/' + customerId + '/execute-search/' + searchId)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('searchResultsContent').innerHTML = html;
                    // Add event listeners to the new content
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
            
            // Show offers modal
            var modal = new bootstrap.Modal(document.getElementById('offersModal'));
            modal.show();
            
            // Load offers
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
    
    // Function to add event listeners to single offer buttons
    function addSingleOfferListeners(customerId, searchId) {
        document.querySelectorAll('.send-single-offer').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var propertyId = this.dataset.propertyId;
                
                if (confirm('Biztosan szeretnéd elküldeni ezt az ingatlant ajánlatként?')) {
                    // Disable button to prevent double clicks
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Küldés...';
                    
                    // Send single property offer
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
                            // Change button to success state
                            this.innerHTML = '<i class="fas fa-check"></i> Elküldve';
                            this.classList.remove('btn-success');
                            this.classList.add('btn-outline-success');
                        } else {
                            alert('Hiba történt az ajánlat küldése során: ' + (data.message || 'Ismeretlen hiba'));
                            // Reset button
                            this.disabled = false;
                            this.innerHTML = 'Kiajánl';
                        }
                    })
                    .catch(error => {
                        alert('Hiba történt az ajánlat küldése során.');
                        // Reset button
                        this.disabled = false;
                        this.innerHTML = 'Kiajánl';
                    });
                }
            });
        });
    }
    
    // Clear modal content when closed
    document.getElementById('searchResultsModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('searchResultsContent').innerHTML = 
            '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Betöltés...</span></div></div>';
    });
    
    document.getElementById('offersModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('offersContent').innerHTML = 
            '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Betöltés...</span></div></div>';
    });
});
</script>
@endpush
@endsection

