<div class="row">
    <div class="col-md-6">
        <h6>Ajánlat információk</h6>
        <table class="table table-sm">
            <tr>
                <td><strong>Vevő:</strong></td>
                <td>{{ $offer->customer->name_0 }}</td>
            </tr>
            <tr>
                <td><strong>Küldés dátuma:</strong></td>
                <td>{{ $offer->sent_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            <tr>
                <td><strong>Email tárgy:</strong></td>
                <td>{{ $offer->email_subject }}</td>
            </tr>
            <tr>
                <td><strong>Ingatlanok száma:</strong></td>
                <td>{{ count($offer->property_ids) }}</td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <h6>Email tartalom</h6>
        <div class="border p-3" style="max-height: 200px; overflow-y: auto;">
            {{ $offer->email_content }}
        </div>
    </div>
</div>

<hr>

<h6>Küldött ingatlanok</h6>
@if($properties->isEmpty())
    <div class="alert alert-warning">Nem találhatók ingatlanok.</div>
@else
    <div class="row">
        @foreach($properties as $property)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">{{ $property->property_code ?? $property->id }}</h6>
                        <p class="card-text">
                            <strong>Típus:</strong> {{ $property->propertyType->name ?? 'N/A' }}<br>
                            <strong>Település:</strong> {{ $property->settlement->name ?? 'N/A' }}<br>
                            <strong>Ár:</strong> {{ number_format($property->price, 0, ',', ' ') }} Ft
                        </p>
                        <a href="{{ url('/admin/property/'.$property->id.'/edit') }}" 
                           class="btn btn-sm btn-outline-primary" target="_blank">
                            Megtekintés
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
