@extends('admin.layouts.app')

@section('title', $title)
@section('page-title', $pageTitle)

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .property-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    }
    
    .property-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .property-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 1.5rem;
    }
    
    .action-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        color: white;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        text-decoration: none;
        color: white;
    }
    
    .btn-edit {
        background: linear-gradient(45deg, #28a745, #20c997);
    }
    
    .btn-toggle {
        background: linear-gradient(45deg, #ffc107, #fd7e14);
    }
    
    .btn-matching {
        background: linear-gradient(45deg, #17a2b8, #6f42c1);
    }
    
    .btn-print {
        background: linear-gradient(45deg, #6f42c1, #e83e8c);
    }
    
    .btn-delete {
        background: linear-gradient(45deg, #dc3545, #e83e8c);
    }
    
    .info-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #f0f0f0;
    }
    
    .info-card h5 {
        color: #495057;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #6c757d;
        min-width: 150px;
    }
    
    .info-value {
        color: #495057;
        text-align: right;
        flex: 1;
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .status-active {
        background: #d4edda;
        color: #155724;
    }
    
    .status-inactive {
        background: #f8d7da;
        color: #721c24;
    }
    
    .status-featured {
        background: #fff3cd;
        color: #856404;
    }
    
    .image-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .image-item {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    
    .image-item:hover {
        transform: scale(1.05);
    }
    
    .image-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }
    
    .map-container {
        height: 400px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #f0f0f0;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #6c757d;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Property Header -->
    <div class="property-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="property-title">{{ $property->title }}</h1>
                <p class="property-subtitle">
                    <strong>{{ $property->property_code }}</strong> • 
                    {{ $property->getFormattedPrice() }} Ft • 
                    {{ $property->getAdType() }}
                </p>
                <p class="property-subtitle">
                    {{ $property->getFullAddress() }}
                </p>
            </div>
            <div class="col-md-4 text-end">
                <div class="action-buttons">
                    <a href="{{ route($editRoute, $property->id) }}" class="action-btn btn-edit">
                        <i class="fas fa-edit"></i> Szerkesztés
                    </a>
                    <button class="action-btn btn-toggle" onclick="toggleActive({{ $property->id }}, {{ $property->is_active ? 'true' : 'false' }})">
                        <i class="fas {{ $property->is_active ? 'fa-times' : 'fa-check' }}"></i> 
                        {{ $property->is_active ? 'Deaktiválás' : 'Aktiválás' }}
                    </button>
                    <a href="{{ route('admin.properties.matching-searches', $property->id) }}" class="action-btn btn-matching">
                        <i class="fas fa-search"></i> Keresések
                    </a>
                    <button class="action-btn btn-print" onclick="window.print()">
                        <i class="fas fa-print"></i> Nyomtatás
                    </button>
                    <form action="{{ route('admin.properties.destroy', $property->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Biztosan törli ezt az ingatlant? Ez a művelet nem vonható vissza!');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn btn-delete">
                            <i class="fas fa-trash"></i> Törlés
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $property->images ? count(json_decode($property->images, true) ?? []) : 0 }}</div>
            <div class="stat-label">Feltöltött képek</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ count($matchingSearches) }}</div>
            <div class="stat-label">Illeszkedő keresések</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $property->attributes->count() }}</div>
            <div class="stat-label">Tulajdonságok</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $property->labels->count() }}</div>
            <div class="stat-label">Címkék</div>
        </div>
    </div>

    <div class="row">
        <!-- Basic Information -->
        <div class="col-md-6">
            <div class="info-card">
                <h5><i class="fas fa-info-circle"></i> Alapadatok</h5>
                <div class="info-row">
                    <span class="info-label">Ingatlan kód:</span>
                    <span class="info-value"><strong>{{ $property->property_code }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Referens:</span>
                    <span class="info-value"><strong>{{ $property->user->name ?? '-' }}</strong></span>
                </div>
                @if($property->client)
                <div class="info-row">
                    <span class="info-label">Megbízó:</span>
                    <span class="info-value"><strong>{{ $property->client->name }}</strong></span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Ár:</span>
                    <span class="info-value"><strong>{{ $property->getFormattedPrice() }} Ft</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Típus:</span>
                    <span class="info-value">{{ $property->getAdType() }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Állapot:</span>
                    <span class="info-value">
                        <span class="status-badge {{ $property->is_active ? 'status-active' : 'status-inactive' }}">
                            {{ $property->is_active ? 'Aktív' : 'Inaktív' }}
                        </span>
                    </span>
                </div>
                @if($property->featured)
                <div class="info-row">
                    <span class="info-label">Kiemelt:</span>
                    <span class="info-value">
                        <span class="status-badge status-featured">Igen</span>
                    </span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Létrehozva:</span>
                    <span class="info-value">{{ $property->created_at->format('Y-m-d H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Módosítva:</span>
                    <span class="info-value">{{ $property->updated_at->format('Y-m-d H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Location Information -->
        <div class="col-md-6">
            <div class="info-card">
                <h5><i class="fas fa-map-marker-alt"></i> Helyszín</h5>
                <div class="info-row">
                    <span class="info-label">Település:</span>
                    <span class="info-value">{{ $property->settlement->fullName ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Településrész:</span>
                    <span class="info-value">{{ $property->settlementPart->name ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ingatlantípus:</span>
                    <span class="info-value">{{ $property->propertyType->name ?? '-' }}</span>
                </div>
                @if($property->propertySubtype)
                <div class="info-row">
                    <span class="info-label">Altípus:</span>
                    <span class="info-value">{{ $property->propertySubtype->name }}</span>
                </div>
                @endif
                @if($property->project)
                <div class="info-row">
                    <span class="info-label">Projekt:</span>
                    <span class="info-value">{{ $property->project->name }}</span>
                </div>
                @endif
                @if($property->address)
                <div class="info-row">
                    <span class="info-label">Cím:</span>
                    <span class="info-value">{{ $property->address }}</span>
                </div>
                @endif
                @if($property->hasCoordinates())
                <div class="info-row">
                    <span class="info-label">Koordináták:</span>
                    <span class="info-value">{{ $property->latitude }}, {{ $property->longitude }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Images -->
    @php
        $images = json_decode($property->images, true) ?? [];
    @endphp
    @if(!empty($images))
    <div class="info-card">
        <h5><i class="fas fa-images"></i> Képek</h5>
        <div class="image-gallery">
            @foreach($images as $image)
            @if(\Illuminate\Support\Facades\Storage::disk('public')->exists($image))
            <div class="image-item">
                <img src="{{ \Illuminate\Support\Facades\Storage::url($image) }}" alt="{{ $property->title }}">
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @endif

    <!-- Map -->
    @if($property->hasCoordinates())
    <div class="info-card">
        <h5><i class="fas fa-map"></i> Térkép</h5>
        <div id="map" class="map-container"></div>
    </div>
    @endif

    <!-- Description -->
    @if($property->description)
    <div class="info-card">
        <h5><i class="fas fa-file-alt"></i> Részletes leírás</h5>
        <div class="mt-3">
            {!! $property->description !!}
        </div>
    </div>
    @endif

    <!-- Internal Comments -->
    @if($property->inner_comments)
    <div class="info-card">
        <h5><i class="fas fa-comment"></i> Belső komment</h5>
        <div class="mt-3">
            {!! $property->inner_comments !!}
        </div>
    </div>
    @endif

    <!-- Labels -->
    @if($property->labels->count() > 0)
    <div class="info-card">
        <h5><i class="fas fa-tags"></i> Címkék</h5>
        <div class="mt-3">
            @foreach($property->labels as $label)
            <span class="badge bg-primary me-2 mb-2">{{ $label->name }}</span>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Attributes -->
    @if($property->attributes->count() > 0)
    <div class="info-card">
        <h5><i class="fas fa-list"></i> Tulajdonságok</h5>
        <div class="mt-3">
            @php
                $attributesByCategory = $property->attributes->groupBy(function($attr) {
                    return $attr->category->name ?? 'Egyéb';
                });
            @endphp
            @foreach($attributesByCategory as $categoryName => $attributes)
                @if($categoryName)
                <h6 class="text-muted mb-3 mt-4">{{ $categoryName }}</h6>
                @endif
                @foreach($attributes as $attribute)
                <div class="info-row">
                    <span class="info-label">{{ $attribute->label }}:</span>
                    <span class="info-value">{{ $property->getFormattedAttributeValue($attribute) }}</span>
                </div>
                @endforeach
            @endforeach
        </div>
    </div>
    @endif

    <!-- Documents -->
    @if($property->documents->count() > 0)
    <div class="info-card">
        <h5><i class="fas fa-file-alt"></i> Dokumentumok</h5>
        <div class="mt-3">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Név</th>
                            <th>Kategória</th>
                            <th>Fájl</th>
                            <th>Méret</th>
                            <th>Feltöltve</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($property->documents as $document)
                        <tr>
                            <td>{{ $document->name }}</td>
                            <td><span class="badge bg-secondary">{{ $document->category_name }}</span></td>
                            <td>
                                <a href="{{ $document->file_url }}" target="_blank">
                                    <i class="fas fa-external-link-alt"></i> {{ $document->original_name }}
                                </a>
                            </td>
                            <td>{{ $document->file_size_human }}</td>
                            <td>{{ $document->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
@if($property->hasCoordinates())
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    const map = L.map('map').setView([{{ $property->latitude }}, {{ $property->longitude }}], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    L.marker([{{ $property->latitude }}, {{ $property->longitude }}]).addTo(map)
        .bindPopup('{{ $property->title }}')
        .openPopup();
</script>
@endif

<script>
function toggleActive(propertyId, isActive) {
    const action = isActive ? 'deaktiválja' : 'aktiválja';
    if (confirm('Biztosan ' + action + ' ezt az ingatlant?')) {
        fetch(`/admin/properties/${propertyId}/toggle-active`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Hiba történt: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hiba történt a művelet során');
        });
    }
}
</script>
@endpush
@endsection

