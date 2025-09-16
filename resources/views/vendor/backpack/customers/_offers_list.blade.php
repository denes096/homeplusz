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
                                <i class="la la-eye"></i> Részletek
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
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="offerDetailsContent">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle offer details button
    document.querySelectorAll('.view-offer-details').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var offerId = this.dataset.offerId;
            
            // Show modal
            $('#offerDetailsModal').modal('show');
            
            // Load offer details
            fetch('{{ url(config('backpack.base.route_prefix')) }}/offers/' + offerId + '/details')
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
    $('#offerDetailsModal').on('hidden.bs.modal', function () {
        // Clear the content when modal is closed
        document.getElementById('offerDetailsContent').innerHTML = 
            '<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Betöltés...</span></div></div>';
    });
    
    // Manual close button handler for offer details modal
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
</script>
