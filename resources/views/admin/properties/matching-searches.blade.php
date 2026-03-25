@extends('admin.layouts.app')

@section('title', $title)
@section('page-title', $pageTitle)

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route($indexRoute) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Vissza az ingatlanokhoz
            </a>
            <a href="{{ route('admin.properties.show', $property->id) }}" class="btn btn-primary">
                <i class="fas fa-eye me-2"></i>Ingatlan megtekintése
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ingatlan részletei</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Kód:</strong> {{ $property->property_code ?? $property->id }}</p>
                            <p><strong>Típus:</strong> {{ $property->propertyType->name ?? 'N/A' }}</p>
                            <p><strong>Település:</strong> {{ $property->settlement->fullName ?? 'N/A' }}</p>
                            <p><strong>Településrész:</strong> {{ $property->settlementPart->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Ár:</strong> {{ $property->getFormattedPrice() }} Ft</p>
                            <p><strong>Hirdetés típusa:</strong> {{ $property->getAdType() }}</p>
                            <p><strong>Altípus:</strong> {{ $property->propertySubtype->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Illeszkedő keresések ({{ count($matches) }} találat)
                        @php
                            $alreadyOfferedCount = 0;
                            foreach($matches as $match) {
                                if($property->isOfferedToCustomer($match['customer']->id, $match['search']->id)) {
                                    $alreadyOfferedCount++;
                                }
                            }
                        @endphp
                        @if($alreadyOfferedCount > 0)
                            <small class="text-warning">- {{ $alreadyOfferedCount }} már kiajánlva</small>
                        @endif
                    </h3>
                </div>
                <div class="card-body">
                    @if(empty($matches))
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Nincsenek illeszkedő keresések ehhez az ingatlanhoz.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Találat pontszám</th>
                                        <th>Vevő</th>
                                        <th>Email</th>
                                        <th>Telefon</th>
                                        <th>Keresés részletei</th>
                                        <th>Műveletek</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($matches as $match)
                                        @php
                                            $search = $match['search'];
                                            $customer = $match['customer'];
                                            $score = $match['match_score'];
                                            $searchParams = json_decode($search->search, true);
                                            $isAlreadyOffered = $property->isOfferedToCustomer($customer->id, $search->id);
                                        @endphp
                                        <tr class="{{ $isAlreadyOffered ? 'table-warning' : '' }}">
                                            <td>
                                                <span class="badge bg-{{ $score >= 15 ? 'success' : ($score >= 10 ? 'warning' : 'info') }}">
                                                    {{ $score }} pont
                                                </span>
                                                @if($isAlreadyOffered)
                                                    <br><small class="text-warning"><i class="fas fa-check-circle"></i> Már kiajánlva</small>
                                                @endif
                                            </td>
                                            <td>{{ $customer->name_0 ?? 'N/A' }}</td>
                                            <td>{{ $customer->email ?? 'N/A' }}</td>
                                            <td>{{ $customer->phone_0 ?? 'N/A' }}</td>
                                            <td>
                                                <small>
                                                    @if(isset($searchParams['p[price_min]']) || isset($searchParams['p[price_max]']))
                                                        <strong>Ár:</strong> 
                                                        {{ isset($searchParams['p[price_min]']) ? number_format($searchParams['p[price_min]'], 0, ',', ' ') : '0' }} - 
                                                        {{ isset($searchParams['p[price_max]']) ? number_format($searchParams['p[price_max]'], 0, ',', ' ') : '∞' }} Ft<br>
                                                    @endif
                                                    @if(isset($searchParams['p[property_types]']))
                                                        <strong>Típus:</strong> {{ implode(', ', $searchParams['p[property_types]']) }}<br>
                                                    @endif
                                                    @if(isset($searchParams['p[settlements]']))
                                                        <strong>Település:</strong> {{ implode(', ', $searchParams['p[settlements]']) }}<br>
                                                    @endif
                                                    @if(isset($searchParams['p[ad_type]']))
                                                        <strong>Hirdetés típusa:</strong> {{ $searchParams['p[ad_type]'] == 'sell' ? 'Eladó' : 'Kiadó' }}<br>
                                                    @endif
                                                </small>
                                            </td>
                                            <td>
                                                @if($isAlreadyOffered)
                                                    <button class="btn btn-sm btn-outline-success" disabled title="Már elküldve">
                                                        <i class="fas fa-check"></i> Elküldve
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-success send-to-search" 
                                                            data-property-id="{{ $property->id }}" 
                                                            data-search-id="{{ $search->id }}"
                                                            data-customer-name="{{ $customer->name_0 }}"
                                                            title="Küldés a vevőnek">
                                                        <i class="fas fa-envelope"></i> Küldés
                                                    </button>
                                                @endif
                                                <a href="{{ route('admin.customers.show', $customer->id) }}" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Vevő megtekintése">
                                                    <i class="fas fa-user"></i>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to update the offered count in the header
    function updateOfferedCount() {
        var offeredCount = document.querySelectorAll('.btn-outline-success[disabled]').length;
        var headerTitle = document.querySelector('.card-title');
        var existingCount = headerTitle.querySelector('.text-warning');
        
        if (offeredCount > 0) {
            if (existingCount) {
                existingCount.textContent = '- ' + offeredCount + ' már kiajánlva';
            } else {
                var countElement = document.createElement('small');
                countElement.className = 'text-warning';
                countElement.textContent = '- ' + offeredCount + ' már kiajánlva';
                headerTitle.appendChild(countElement);
            }
        }
    }
    
    // Handle send to search buttons
    document.querySelectorAll('.send-to-search').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            var propertyId = this.dataset.propertyId;
            var searchId = this.dataset.searchId;
            var customerName = this.dataset.customerName;
            
            if (confirm('Biztosan szeretnéd elküldeni ezt az ingatlant a következő vevőnek: ' + customerName + '?')) {
                // Disable button to prevent double clicks
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Küldés...';
                
                // Send property to search
                fetch(`/admin/properties/{{ $property->id }}/send-to-search/${searchId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Change button to success state
                        this.innerHTML = '<i class="fas fa-check"></i> Elküldve';
                        this.classList.remove('btn-success');
                        this.classList.add('btn-outline-success');
                        this.disabled = true;
                        this.title = 'Már elküldve';
                        
                        // Add "Már kiajánlva" indicator to the score column
                        var scoreCell = this.closest('tr').querySelector('td:first-child');
                        var existingIndicator = scoreCell.querySelector('.text-warning');
                        if (!existingIndicator) {
                            var indicator = document.createElement('small');
                            indicator.className = 'text-warning';
                            indicator.innerHTML = '<br><i class="fas fa-check-circle"></i> Már kiajánlva';
                            scoreCell.appendChild(indicator);
                        }
                        
                        // Add warning class to the row
                        this.closest('tr').classList.add('table-warning');
                        
                        // Update the header count
                        updateOfferedCount();
                        
                        // Show success message
                        alert(data.message || 'Ajánlat sikeresen elküldve');
                    } else {
                        alert('Hiba történt az ajánlat küldése során: ' + (data.message || 'Ismeretlen hiba'));
                        // Reset button
                        this.disabled = false;
                        this.innerHTML = '<i class="fas fa-envelope"></i> Küldés';
                    }
                })
                .catch(error => {
                    alert('Hiba történt az ajánlat küldése során.');
                    // Reset button
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-envelope"></i> Küldés';
                });
            }
        });
    });
});
</script>
@endpush
@endsection

