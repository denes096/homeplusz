@extends('admin.layouts.app')

@section('title', $title)
@section('page-title', $pageTitle)

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>{{ $pageTitle }}</h4>
            <a href="{{ route('admin.properties.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Új ingatlan
            </a>
        </div>
        <div class="card-body">
            <!-- Filters and Search -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.properties.index', ['filter' => 'all'] + request()->except('filter')) }}" 
                           class="btn btn-sm {{ $currentFilter === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                            Összes
                        </a>
                        <a href="{{ route('admin.properties.index', ['filter' => 'active'] + request()->except('filter')) }}" 
                           class="btn btn-sm {{ $currentFilter === 'active' ? 'btn-primary' : 'btn-outline-primary' }}">
                            Aktív
                        </a>
                        <a href="{{ route('admin.properties.index', ['filter' => 'inactive'] + request()->except('filter')) }}" 
                           class="btn btn-sm {{ $currentFilter === 'inactive' ? 'btn-primary' : 'btn-outline-primary' }}">
                            Inaktív
                        </a>
                        <a href="{{ route('admin.properties.index', ['filter' => 'own'] + request()->except('filter')) }}" 
                           class="btn btn-sm {{ $currentFilter === 'own' ? 'btn-primary' : 'btn-outline-primary' }}">
                            Saját
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <form method="GET" action="{{ route('admin.properties.index') }}" class="d-flex">
                        <input type="hidden" name="filter" value="{{ $currentFilter }}">
                        <input type="text" name="search" class="form-control me-2" placeholder="Keresés (kód, cím, referens, település)..." value="{{ $searchTerm }}">
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-search"></i>
                        </button>
                        @if($searchTerm)
                        <a href="{{ route('admin.properties.index', ['filter' => $currentFilter]) }}" class="btn btn-outline-danger ms-2">
                            <i class="fas fa-times"></i>
                        </a>
                        @endif
                    </form>
                </div>
            </div>

            @if($properties->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 150px;">Kép</th>
                                <th>Ingatlan kód</th>
                                <th>Cím</th>
                                <th>Ár</th>
                                <th>Típus</th>
                                <th>Település</th>
                                <th>Településrész</th>
                                <th>Ingatlantípus</th>
                                <th>Aktív</th>
                                <th>Kiemelt</th>
                                <th>Referens</th>
                                <th>Létrehozva</th>
                                <th class="text-end">Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($properties as $property)
                            <tr>
                                <td>
                                    <img src="{{ $property->first_image_url }}" alt="" style="max-width: 150px; max-height: 100px; object-fit: cover; border-radius: 4px;">
                                </td>
                                <td>
                                    <strong>{{ $property->property_code }}</strong>
                                </td>
                                <td>
                                    <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $property->title }}">
                                        {{ $property->title }}
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $property->getFormattedPrice() }} Ft</strong>
                                </td>
                                <td>
                                    @if($property->ad_type === 'sell')
                                        <span class="badge bg-success">Eladó</span>
                                    @elseif($property->ad_type === 'rent')
                                        <span class="badge bg-info">Kiadó</span>
                                    @else
                                        <span class="badge bg-warning">Eladó/Kiadó</span>
                                    @endif
                                </td>
                                <td>{{ $property->settlement->fullName ?? '-' }}</td>
                                <td>{{ $property->settlementPart->name ?? '-' }}</td>
                                <td>{{ $property->propertyType->name ?? '-' }}</td>
                                <td>
                                    @if($property->is_active)
                                        <span class="badge bg-success">Igen</span>
                                    @else
                                        <span class="badge bg-secondary">Nem</span>
                                    @endif
                                </td>
                                <td>
                                    @if($property->featured)
                                        <span class="badge bg-warning">Igen</span>
                                    @else
                                        <span class="badge bg-secondary">Nem</span>
                                    @endif
                                </td>
                                <td>{{ $property->user->name ?? '-' }}</td>
                                <td>{{ $property->created_at->format('Y-m-d H:i') }}</td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.properties.show', $property->id) }}" class="btn btn-sm btn-info" title="Megtekintés">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.properties.edit', $property->id) }}" class="btn btn-sm btn-primary" title="Szerkesztés">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm {{ $property->is_active ? 'btn-warning' : 'btn-success' }} toggle-active-btn" 
                                                data-property-id="{{ $property->id }}"
                                                title="{{ $property->is_active ? 'Deaktiválás' : 'Aktiválás' }}">
                                            <i class="fas {{ $property->is_active ? 'fa-times' : 'fa-check' }}"></i>
                                        </button>
                                        <a href="{{ route('admin.properties.matching-searches', $property->id) }}" class="btn btn-sm btn-secondary" title="Illeszkedő keresések">
                                            <i class="fas fa-search"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-3">
                    {{ $properties->links() }}
                </div>
            @else
                <div class="empty-state text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h4>Nincs találat</h4>
                    <p class="text-muted">Még nincsenek ingatlanok ebben a listában.</p>
                    <a href="{{ route('admin.properties.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Első ingatlan hozzáadása
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Toggle active functionality
    document.querySelectorAll('.toggle-active-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const propertyId = this.getAttribute('data-property-id');
            
            if (!confirm('Biztosan meg szeretné változtatni az ingatlan aktív állapotát?')) {
                return;
            }
            
            fetch(`/admin/properties/${propertyId}/toggle-active`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload page to update the UI
                    location.reload();
                } else {
                    alert('Hiba: ' + (data.message || 'Ismeretlen hiba'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hiba történt a művelet során');
            });
        });
    });
});
</script>
@endpush
@endsection

