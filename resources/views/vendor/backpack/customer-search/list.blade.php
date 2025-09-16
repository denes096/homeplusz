@extends(backpack_view('blank'))

@section('header')
    <section class="content-header">
        <h1>Mentett keresések</h1>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if($crud->hasAccess('create'))
                        <div class="mb-3">
                            <a href="{{ url($crud->route.'/create') }}" class="btn btn-primary">
                                <i class="la la-plus"></i> Új keresés
                            </a>
                        </div>
                    @endif

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
                                @forelse($entries as $entry)
                                    <tr>
                                        <td>{{ $entry->id }}</td>
                                        <td>{{ $entry->customer->name_0 ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ url(config('backpack.base.route_prefix').'/customers/'.$entry->customer_id.'/show') }}" 
                                               class="btn btn-sm btn-info" title="Vevő megtekintése">
                                                <i class="la la-user"></i>
                                            </a>
                                            <button class="btn btn-sm btn-primary execute-search" 
                                                    data-customer-id="{{ $entry->customer_id }}" 
                                                    data-search-id="{{ $entry->id }}"
                                                    title="Keresés futtatása">
                                                <i class="la la-play"></i> Futtatás
                                            </button>
                                            <button class="btn btn-sm btn-success view-offers" 
                                                    data-customer-id="{{ $entry->customer_id }}" 
                                                    data-search-id="{{ $entry->id }}"
                                                    title="Kiküldött ajánlatok megtekintése">
                                                <i class="la la-envelope"></i> Ajánlatok
                                            </button>
                                            @if($crud->hasAccess('update'))
                                                <a href="{{ url($crud->route.'/'.$entry->id.'/edit') }}" 
                                                   class="btn btn-sm btn-warning" title="Szerkesztés">
                                                    <i class="la la-edit"></i>
                                                </a>
                                            @endif
                                            @if($crud->hasAccess('delete'))
                                                <a href="javascript:void(0)" 
                                                   onclick="deleteEntry(this)" 
                                                   data-route="{{ url($crud->route.'/'.$entry->id) }}" 
                                                   class="btn btn-sm btn-danger" title="Törlés">
                                                    <i class="la la-trash"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <div class="alert alert-info">Nincsenek mentett keresések.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $entries->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after_scripts')
    <!-- Search Results Modal - placed at end to avoid z-index issues -->
    <div class="modal fade" id="searchResultsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Keresési eredmények</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="searchResultsContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Betöltés...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Bezárás</button>
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
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="offersContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Betöltés...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Bezárás</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('after_styles')
<style>
/* Modal should work properly now that it's placed at the end of the DOM */
</style>
@endpush

@push('after_scripts')
<script>
// Wait for both DOM and jQuery to be ready
document.addEventListener('DOMContentLoaded', function () {
    // Ensure jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not available');
        return;
    }
    // Handle search execution
    document.querySelectorAll('.execute-search').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var customerId = this.dataset.customerId;
            var searchId = this.dataset.searchId;
            
            console.log('Executing search for customer:', customerId, 'search:', searchId);
            
            // Show modal
            $('#searchResultsModal').modal('show');
            
            // Load search results
            fetch('{{ url(config('backpack.base.route_prefix')) }}' + '/customers/' + customerId + '/execute-search/' + searchId)
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
            
            console.log('Viewing offers for customer:', customerId, 'search:', searchId);
            
            // Show offers modal
            $('#offersModal').modal('show');
            
            // Load offers
            fetch('{{ url(config('backpack.base.route_prefix')) }}' + '/customers/' + customerId + '/offers/' + searchId)
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
                    this.innerHTML = '<i class="la la-spinner la-spin"></i> Küldés...';
                    
                    // Send single property offer
                    fetch('{{ url(config('backpack.base.route_prefix')) }}' + '/customers/' + customerId + '/send-offer', {
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
                            this.innerHTML = '<i class="la la-check"></i> Elküldve';
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
    
    // Handle modal close button and backdrop click
    $('#searchResultsModal').on('hidden.bs.modal', function () {
        // Clear the content when modal is closed
        document.getElementById('searchResultsContent').innerHTML = 
            '<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Betöltés...</span></div></div>';
    });
    
    // Handle offers modal close
    $('#offersModal').on('hidden.bs.modal', function () {
        // Clear the content when modal is closed
        document.getElementById('offersContent').innerHTML = 
            '<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Betöltés...</span></div></div>';
    });
    
    // Manual close button handler as fallback for both modals
    document.addEventListener('click', function(e) {
        if (e.target.matches('[data-dismiss="modal"]') || e.target.closest('[data-dismiss="modal"]')) {
            // Find which modal the close button belongs to
            var modal = e.target.closest('.modal');
            if (modal) {
                $(modal).modal('hide');
            }
        }
    });
});

// Delete entry function
function deleteEntry(button) {
    if (confirm('Biztosan törölni szeretné ezt a keresést?')) {
        var route = button.dataset.route;
        fetch(route, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Hiba történt a törlés során.');
            }
        });
    }
}
</script>
@endpush
