@if($offers->isEmpty())
    <div class="alert alert-info">Nincsenek kiküldött ajánlatok.</div>
@else
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Küldés dátuma</th>
                    <th>Ingatlanok száma</th>
                    <th>Email tárgy</th>
                    <th>Műveletek</th>
                </tr>
            </thead>
            <tbody>
                @foreach($offers as $offer)
                    <tr>
                        <td>{{ $offer->sent_at->format('Y-m-d H:i') }}</td>
                        <td>{{ count($offer->property_ids) }}</td>
                        <td>{{ $offer->email_subject }}</td>
                        <td>
                            <button class="btn btn-sm btn-info view-offer-details" 
                                    data-offer-id="{{ $offer->id }}"
                                    title="Részletek megtekintése">
                                <i class="fas fa-eye"></i> Részletek
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

<!-- Offer Details Modal -->
<div class="modal fade" id="offerDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle offer details button
    document.querySelectorAll('.view-offer-details').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var offerId = this.dataset.offerId;
            var modal = new bootstrap.Modal(document.getElementById('offerDetailsModal'));
            modal.show();
            
            // Load offer details
            fetch('/admin/offers/' + offerId + '/details')
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
    
    // Handle offer details modal close
    document.getElementById('offerDetailsModal').addEventListener('hidden.bs.modal', function () {
        // Clear the content when modal is closed
        document.getElementById('offerDetailsContent').innerHTML = 
            '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Betöltés...</span></div></div>';
    });
});
</script>

