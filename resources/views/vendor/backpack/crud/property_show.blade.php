@extends(backpack_view('blank'))

@php
    $property = $entry;
    $propertyService = new \App\Services\PropertyService();
    $matchingSearches = $propertyService->findMatchingCustomerSearches($property);
    $activityLogService = new \App\Services\ActivityLogService();
    $propertyActivities = $activityLogService->getPropertyActivities($property->id, 10);
@endphp

@section('content')
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
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            text-decoration: none;
            color: white;
        }
        
        .btn-edit {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
        }
        
        .btn-toggle {
            background: linear-gradient(45deg, #ffc107, #fd7e14);
            color: white;
        }
        
        .btn-matching {
            background: linear-gradient(45deg, #17a2b8, #6f42c1);
            color: white;
        }
        
        .btn-images {
            background: linear-gradient(45deg, #6c757d, #495057);
            color: white;
        }
        
        .btn-print {
            background: linear-gradient(45deg, #6f42c1, #e83e8c);
            color: white;
        }
        
        .btn-delete {
            background: linear-gradient(45deg, #dc3545, #e83e8c);
            color: white;
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
        
        .timeline {
            position: relative;
            margin: 2rem 0;
            padding-left: 40px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            top: 0;
            left: 15px;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #667eea, #764ba2);
            border-radius: 2px;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
            padding-left: 20px;
        }
        
        .timeline-icon {
            position: absolute;
            left: 0;
            top: 0;
            background: #667eea;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            text-align: center;
            line-height: 30px;
            font-weight: bold;
            box-shadow: 0 0 8px rgba(102, 126, 234, 0.7);
        }
        
        .timeline-content {
            background: #fff;
            padding: 1rem 1.25rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.15);
        }
        
        .timeline-time {
            font-size: 0.85rem;
            color: #666;
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
        
        .map-container {
            height: 400px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        /* Print styles */
        @media print {
            .action-buttons,
            .btn,
            .timeline,
            .stats-grid {
                display: none !important;
            }
            
            .property-header {
                background: #f8f9fa !important;
                color: #000 !important;
                border: 2px solid #000;
                margin-bottom: 20px;
            }
            
            .info-card {
                border: 1px solid #ddd;
                margin-bottom: 15px;
                page-break-inside: avoid;
            }
            
            .info-card h5 {
                border-bottom: 2px solid #000;
                padding-bottom: 5px;
                margin-bottom: 10px;
            }
            
            .info-row {
                border-bottom: 1px solid #eee;
                padding: 5px 0;
            }
            
            /* Show images in print */
            .image-gallery {
                display: grid !important;
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
                margin: 10px 0;
            }
            
            .image-item {
                border: 1px solid #ddd;
                padding: 5px;
                text-align: center;
            }
            
            .image-item img {
                max-width: 100%;
                height: auto;
                max-height: 150px;
                object-fit: cover;
            }
            
            /* Show map in print */
            .map-container {
                height: 300px !important;
                border: 1px solid #ddd;
                margin: 10px 0;
            }
            
            /* Show static map in print */
            .static-map {
                display: block !important;
                margin: 10px 0;
            }
            
            /* Ensure map tiles are visible */
            .leaflet-tile {
                opacity: 1 !important;
                visibility: visible !important;
            }
            
            body {
                font-size: 12px;
                line-height: 1.4;
            }
            
            .container-fluid {
                max-width: none;
                padding: 0;
            }
            
            /* Force visibility of all content */
            * {
                visibility: visible !important;
            }
        }
    </style>

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
                        <a href="{{ backpack_url('property/'.$property->id.'/edit') }}" class="action-btn btn-edit">
                            <i class="la la-edit"></i> Szerkesztés
                        </a>
                        <button class="action-btn btn-toggle" onclick="toggleActive({{ $property->id }})">
                            <i class="la {{ $property->is_active ? 'la-times' : 'la-check' }}"></i> 
                            {{ $property->is_active ? 'Deaktiválás' : 'Aktiválás' }}
                        </button>
                        <a href="{{ backpack_url('property/'.$property->id.'/matching-searches') }}" class="action-btn btn-matching">
                            <i class="la la-search"></i> Keresések
                        </a>
                        <a href="{{ backpack_url('property-image-downloader/'.$property->property_code) }}" class="action-btn btn-images">
                            <i class="la la-download"></i> Képek
                        </a>
                        <button class="action-btn btn-print" onclick="printProperty()">
                            <i class="la la-print"></i> Nyomtatás
                        </button>
                        <button class="action-btn btn-delete" onclick="deleteProperty({{ $property->id }})">
                            <i class="la la-trash"></i> Törlés
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $property->images ? count(json_decode($property->images)) : 0 }}</div>
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
                    <h5><i class="la la-info-circle"></i> Alapadatok</h5>
                    <div class="info-row">
                        <span class="info-label">Ingatlan kód:</span>
                        <span class="info-value"><strong>{{ $property->property_code }}</strong></span>
                    </div>
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
                    <h5><i class="la la-map-marker"></i> Helyszín</h5>
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
                    <div class="info-row">
                        <span class="info-label">Altípus:</span>
                        <span class="info-value">{{ $property->propertySubtype->name ?? '-' }}</span>
                    </div>
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
        @if($property->images)
        <div class="info-card">
            <h5><i class="la la-images"></i> Képek</h5>
            <div class="image-gallery">
                @foreach(json_decode($property->images) as $image)
                <div class="image-item">
                    <img src="{{ Storage::url('uploads/'.$property->id.'/'.$image) }}" alt="{{ $property->title }}">
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Map -->
        @if($property->hasCoordinates())
        <div class="info-card">
            <h5><i class="la la-map"></i> Térkép</h5>
            <div id="map" class="map-container"></div>
            <!-- Static map for print -->
            <div class="static-map" style="display: none;">
                <img src="https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/pin-s+ff0000({{ $property->longitude }},{{ $property->latitude }})/{{ $property->longitude }},{{ $property->latitude }},15,0/600x300@2x?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw" 
                     alt="Térkép: {{ $property->address }}" 
                     style="width: 100%; height: 300px; object-fit: cover; border: 1px solid #ddd;">
            </div>
        </div>
        @endif

        <!-- Description -->
        @if($property->description)
        <div class="info-card">
            <h5><i class="la la-file-text"></i> Részletes leírás</h5>
            <div class="mt-3">
                {!! $property->description !!}
            </div>
        </div>
        @endif

        <!-- Internal Comments -->
        @if($property->inner_comments)
        <div class="info-card">
            <h5><i class="la la-comment"></i> Belső komment</h5>
            <div class="mt-3">
                {!! $property->inner_comments !!}
            </div>
        </div>
        @endif

        <!-- Labels -->
        @if($property->labels->count() > 0)
        <div class="info-card">
            <h5><i class="la la-tags"></i> Címkék</h5>
            <div class="mt-3">
                @foreach($property->labels as $label)
                <span class="badge badge-primary mr-2 mb-2">{{ $label->name }}</span>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Attributes -->
        @if($property->attributes->count() > 0)
        <div class="info-card">
            <h5><i class="la la-list"></i> Tulajdonságok</h5>
            <div class="mt-3">
                @php
                    $attributesByCategory = $property->attributes->groupBy('category.name');
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

        <!-- Activity Timeline -->
        <div class="info-card">
            <h5><i class="la la-history"></i> Műveleti előzmények</h5>
            <div class="timeline">
                @forelse($propertyActivities as $activity)
                <div class="timeline-item">
                    <div class="timeline-icon">{{ $activityLogService->getActivityIcon($activity->event) }}</div>
                    <div class="timeline-content">
                        <strong>{{ $activityLogService->formatActivityDescription($activity) }}</strong>
                        @if($activityLogService->getChangedFields($activity))
                        <br><small class="text-muted">Módosított mezők: {{ $activityLogService->getChangedFields($activity) }}</small>
                        @endif
                        <div class="timeline-time">{{ $activity->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                </div>
                @empty
                <div class="timeline-item">
                    <div class="timeline-icon">📝</div>
                    <div class="timeline-content">
                        <strong>Még nincsenek műveleti előzmények</strong>
                        <div class="timeline-time">-</div>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Leaflet Map Script -->
    @if($property->hasCoordinates())
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Initialize map
        const map = L.map('map').setView([{{ $property->latitude }}, {{ $property->longitude }}], 15);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        L.marker([{{ $property->latitude }}, {{ $property->longitude }}]).addTo(map)
            .bindPopup('{{ $property->title }}')
            .openPopup();
    </script>
    @endif

    <!-- JavaScript for actions -->
    <script>
        function toggleActive(propertyId) {
            if (confirm('Biztosan {{ $property->is_active ? "deaktiválja" : "aktiválja" }} ezt az ingatlant?')) {
                fetch(`/admin/property/${propertyId}/toggle-active`, {
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

        function deleteProperty(propertyId) {
            if (confirm('Biztosan törli ezt az ingatlant? Ez a művelet nem vonható vissza!')) {
                fetch(`/admin/property/${propertyId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (response.ok) {
                        window.location.href = '/admin/property';
                    } else {
                        alert('Hiba történt a törlés során');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hiba történt a törlés során');
                });
            }
        }

        function printProperty() {
            // Add print header with property info
            const printHeader = document.createElement('div');
            printHeader.innerHTML = `
                <div style="text-align: center; margin-bottom: 20px; padding: 10px; border-bottom: 2px solid #000;">
                    <h2>INGATLAN ADATLAP</h2>
                    <p><strong>{{ $property->property_code }}</strong> - {{ $property->title }}</p>
                    <p>Nyomtatva: ${new Date().toLocaleDateString('hu-HU')} ${new Date().toLocaleTimeString('hu-HU')}</p>
                </div>
            `;
            
            // Insert header at the beginning of the content
            const content = document.querySelector('.container-fluid');
            content.insertBefore(printHeader, content.firstChild);
            
            // Show static map for print
            const staticMap = document.querySelector('.static-map');
            if (staticMap) {
                staticMap.style.display = 'block';
            }
            
            // Ensure map is fully loaded before printing
            if (typeof map !== 'undefined') {
                map.invalidateSize();
                setTimeout(() => {
                    // Force map tiles to load
                    map.eachLayer(function(layer) {
                        if (layer instanceof L.TileLayer) {
                            layer.redraw();
                        }
                    });
                    
                    // Wait a bit for tiles to load, then print
                    setTimeout(() => {
                        window.print();
                        printHeader.remove();
                        if (staticMap) {
                            staticMap.style.display = 'none';
                        }
                    }, 1000);
                }, 500);
            } else {
                // If no map, print immediately
                setTimeout(() => {
                    window.print();
                    printHeader.remove();
                    if (staticMap) {
                        staticMap.style.display = 'none';
                    }
                }, 500);
            }
        }
    </script>
@endsection
