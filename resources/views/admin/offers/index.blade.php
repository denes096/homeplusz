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
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Vevő</th>
                            <th>Keresés ID</th>
                            <th>Ingatlanok száma</th>
                            <th>Küldve</th>
                            <th>Műveletek</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($offers as $offer)
                            <tr>
                                <td>{{ $offer->id }}</td>
                                <td>
                                    <a href="{{ route('admin.customers.show', $offer->customer_id) }}">
                                        {{ $offer->customer->name_0 ?? 'N/A' }}
                                    </a>
                                </td>
                                <td>{{ $offer->customer_search_id }}</td>
                                <td>{{ is_array($offer->property_ids) ? count($offer->property_ids) : 0 }}</td>
                                <td>{{ $offer->sent_at ? $offer->sent_at->format('Y-m-d H:i:s') : '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary view-offer-details" 
                                            data-offer-id="{{ $offer->id }}"
                                            title="Részletek megtekintése">
                                        <i class="fas fa-eye"></i> Részletek
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="alert alert-info">Nincsenek ajánlatok.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $offers->links() }}
        </div>
    </div>
</div>

<!-- Offer Details Modal -->
<div class="modal fade" id="offerDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajánlat részletei</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="offerDetailsContent">
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
    // Handle view offer details
    document.querySelectorAll('.view-offer-details').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var offerId = this.dataset.offerId;
            
            var modal = new bootstrap.Modal(document.getElementById('offerDetailsModal'));
            modal.show();
            
            fetch('{{ url('admin') }}/offers/' + offerId + '/details')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('offerDetailsContent').innerHTML = html;
                })
                .catch(error => {
                    document.getElementById('offerDetailsContent').innerHTML = 
                        '<div class="alert alert-danger">Hiba történt az ajánlat részleteinek betöltése során.</div>';
                });
        });
    });
    
    // Clear modal content when closed
    document.getElementById('offerDetailsModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('offerDetailsContent').innerHTML = 
            '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Betöltés...</span></div></div>';
    });
});
</script>
@endpush
@endsection

