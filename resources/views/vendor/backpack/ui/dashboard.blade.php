@extends(backpack_view('blank'))

@php
    $user = \Illuminate\Support\Facades\Auth::user();
    $activityLogService = new \App\Services\ActivityLogService();
    $recentActivities = $activityLogService->getRecentActivities(10);
    
    // Real statistics
    $totalProperties = \App\Models\Property::count();
    $activeProperties = \App\Models\Property::where('is_active', true)->count();
    $inactiveProperties = \App\Models\Property::where('is_active', false)->count();
    $sajatProperties = \App\Models\Property::where('user_id', $user->id)->count();
    $newPropertiesLast7Days = \App\Models\Property::where('created_at', '>=', now()->subDays(7))->count();
    $totalCustomers = \App\Models\Customers::count(); // Customers table doesn't have timestamps
    
    Widget::add([
        'type'        => 'jumbotron',
        'heading'     => "Üdvözöllek $user->name!",
        'heading_class' => 'display-3 '.(backpack_theme_config('layout') === 'horizontal_overlap' ? ' text-white' : ''),
        'content'     => '',
        'content_class' => backpack_theme_config('layout') === 'horizontal_overlap' ? 'text-white' : '',
        'button_link' => backpack_url('logout'),
        'button_text' => trans('Kijelentkezés'),
    ]);

@endphp

@section('content')
    <!-- FontAwesome & Bootstrap (ha még nincs) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        .search-container {
            position: relative;
            margin-bottom: 2rem;
        }

        .search-input {
            height: 50px;
            border-radius: 30px;
            padding-left: 45px;
            border: 1px solid #ddd;
            box-shadow: none;
        }

        .search-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #888;
            font-size: 16px;
        }

        .card-header h3 {
            margin: 0;
            font-size: 1.5rem;
        }

        .action-card .btn {
            height: 80px;
            font-weight: 500;
            font-size: 1rem;
            border-radius: 15px;
        }

        .action-card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
        }

        .action-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .action-icon {
            margin-right: 10px;
        }

        .property-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 10px 10px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .property-dropdown-item {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.2s;
        }

        .property-dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .property-dropdown-item:last-child {
            border-bottom: none;
        }

        .property-code {
            font-weight: bold;
            color: #007bff;
        }

        .property-title {
            color: #666;
            font-size: 0.9em;
        }
    </style>

    <div class="container my-4">
        <div class="card mb-4">
            <div class="card-header text-center bg-white border-bottom-0">
                <h3>Ingatlan/Projekt keresése kód alapján</h3>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="search-container">
                            <input type="text" id="property-search" class="form-control search-input" placeholder="Keresés ingatlan kód vagy cím alapján..." value="">
                            <i class="fas fa-search search-icon"></i>
                            <div id="property-dropdown" class="property-dropdown" style="display: none;">
                                <!-- Dropdown tartalom -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ingatlan gombok -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card action-card">
                    <div class="card-body p-3">
                        <a href="/admin/property" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-tools action-icon"></i> Ingatlan karbantartás
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card action-card">
                    <div class="card-body p-3">
                        <a href="/admin/property/create" class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-plus action-icon"></i> Új ingatlan felvitele
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card action-card">
                    <div class="card-body p-3">
                        <a href="/admin/property-image-downloader" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-download action-icon"></i> Képletöltés
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vevő gombok -->
        <div class="row g-4">
            <div class="col-md-4 offset-md-2">
                <div class="card action-card">
                    <div class="card-body p-3">
                        <a href="{{ backpack_url('customer/create') }}" class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-user-plus action-icon"></i> Új vevő felvitele
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card action-card">
                    <div class="card-body p-3">
                        <a href="{{ backpack_url('customer') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-user-cog action-icon"></i> Vevő karbantartás
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container my-5">
        <!-- Statisztikai kártyák -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Összes ingatlan</h5>
                        <h2 class="card-text">{{ $totalProperties }}</h2>
                        <p class="mb-0"><i class="fas fa-home me-2"></i>Frissítve: ma</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Aktív ingatlanok</h5>
                        <h2 class="card-text">{{ $activeProperties }}</h2>
                        <p class="mb-0"><i class="fas fa-check-circle me-2"></i>Jelenleg aktív</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Inaktív ingatlanok</h5>
                        <h2 class="card-text">{{ $inactiveProperties }}</h2>
                        <p class="mb-0"><i class="fas fa-pause-circle me-2"></i>Jelenleg inaktív</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Saját ingatlanok</h5>
                        <h2 class="card-text">{{ $sajatProperties }}</h2>
                        <p class="mb-0"><i class="fas fa-pause-circle me-2"></i>Saját ingatlanok</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Új ingatlanok</h5>
                        <h2 class="card-text">{{ $newPropertiesLast7Days }}</h2>
                        <p class="mb-0"><i class="fas fa-plus-circle me-2"></i>Az elmúlt 7 napban</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- További statisztikák -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card text-white bg-secondary shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Összes vevő</h5>
                        <h2 class="card-text">{{ $totalCustomers }}</h2>
                        <p class="mb-0"><i class="fas fa-users me-2"></i>Az adatbázisban</p>
                        <small class="text-light">*Új vevők számlálása nem elérhető (nincs időbélyeg)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-dark shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Aktív/Inaktív arány</h5>
                        <h2 class="card-text">{{ $totalProperties > 0 ? round(($activeProperties / $totalProperties) * 100, 1) : 0 }}%</h2>
                        <p class="mb-0"><i class="fas fa-chart-pie me-2"></i>Aktív ingatlanok aránya</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chartok -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <strong>Ingatlanok havi bontásban</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="propertyChart" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <strong>Új vevők alakulása</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="buyerChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Property search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('property-search');
            const dropdown = document.getElementById('property-dropdown');
            let searchTimeout;
            
            // Clear any existing search value on page load
            searchInput.value = '';
            dropdown.style.display = 'none';
            
            // Clear any URL search parameters that might cause issues
            if (window.location.search.includes('search=')) {
                const url = new URL(window.location);
                url.searchParams.delete('search');
                window.history.replaceState({}, document.title, url.pathname);
            }

            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                // Clear previous timeout
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    dropdown.style.display = 'none';
                    return;
                }

                // Debounce search
                searchTimeout = setTimeout(() => {
                    searchProperties(query);
                }, 300);
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });

            function searchProperties(query) {
                fetch(`/admin/api/property-search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        displayResults(data);
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        dropdown.innerHTML = '<div class="property-dropdown-item">Hiba történt a keresés során</div>';
                        dropdown.style.display = 'block';
                    });
            }

            function displayResults(properties) {
                if (properties.length === 0) {
                    dropdown.innerHTML = '<div class="property-dropdown-item">Nincs találat</div>';
                    dropdown.style.display = 'block';
                    return;
                }

                dropdown.innerHTML = properties.map(property => {
                    const code = property.property_code.replace(/'/g, "\\'");
                    const title = property.title.replace(/'/g, "\\'").replace(/"/g, '&quot;');
                    return `
                        <div class="property-dropdown-item" onclick="selectProperty('${code}', '${title}')">
                            <div class="property-code">${property.property_code}</div>
                            <div class="property-title">${property.title}</div>
                        </div>
                    `;
                }).join('');

                dropdown.style.display = 'block';
            }

            // Make selectProperty globally available
            window.selectProperty = function(code, title) {
                searchInput.value = `${code} - ${title}`;
                dropdown.style.display = 'none';
                
                // Find the property by code and redirect to show page
                fetch(`/admin/api/property-search?q=${encodeURIComponent(code)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            // Redirect to the first matching property's show page
                            window.location.href = `/admin/property/${data[0].id}/show`;
                        } else {
                            // If not found, go to property list and let user search manually
                            window.location.href = `/admin/property`;
                        }
                    })
                    .catch(error => {
                        console.error('Error finding property:', error);
                        // Fallback to property list
                        window.location.href = `/admin/property`;
                    });
            };
        });
    </script>
    <script>
        // Ingatlan chart (oszlopdiagram)
        const propertyChart = new Chart(document.getElementById('propertyChart'), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Már', 'Ápr', 'Máj', 'Jún'],
                datasets: [{
                    label: 'Ingatlanok',
                    data: [12, 19, 8, 15, 22, 30],
                    backgroundColor: 'rgba(13, 110, 253, 0.7)',
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Vevő chart (vonaldiagram)
        const buyerChart = new Chart(document.getElementById('buyerChart'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Már', 'Ápr', 'Máj', 'Jún'],
                datasets: [{
                    label: 'Új vevők',
                    data: [5, 9, 7, 14, 10, 16],
                    fill: false,
                    borderColor: 'rgba(25, 135, 84, 1)',
                    backgroundColor: 'rgba(25, 135, 84, 0.3)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>


    <style>
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
            background: linear-gradient(180deg, #96006B, #FF4081);
            border-radius: 2px;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
            padding-left: 20px;
        }
        .timeline-item:last-child {
            margin-bottom: 0;
        }
        .timeline-icon {
            position: absolute;
            left: 0;
            top: 0;
            background: #96006B;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            text-align: center;
            line-height: 30px;
            font-weight: bold;
            box-shadow: 0 0 8px rgba(150, 0, 107, 0.7);
        }
        .timeline-content {
            background: #fff;
            padding: 1rem 1.25rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgb(150 0 107 / 0.15);
            transition: box-shadow 0.3s ease;
        }
        .timeline-content:hover {
            box-shadow: 0 8px 16px rgb(150 0 107 / 0.3);
        }
        .timeline-time {
            font-size: 0.85rem;
            color: #666;
        }
        @media (max-width: 576px) {
            .timeline {
                padding-left: 25px;
            }
            .timeline-icon {
                left: -10px;
                width: 24px;
                height: 24px;
                line-height: 24px;
                font-size: 0.9rem;
            }
        }
    </style>

    <div class="container my-4">
        <h4 class="mb-4 text-primary">Legutóbbi események</h4>
        <div class="timeline">
            @forelse($recentActivities as $activity)
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
                        <strong>Még nincsenek események</strong>
                        <div class="timeline-time">-</div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

@endsection


