@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <h5>Összes ingatlan</h5>
                <h2>{{ $stats['total_properties'] }}</h2>
                <p><i class="fas fa-home me-2"></i>Frissítve: ma</p>
                <i class="fas fa-building"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card success">
                <h5>Aktív ingatlanok</h5>
                <h2>{{ $stats['active_properties'] }}</h2>
                <p><i class="fas fa-check-circle me-2"></i>Jelenleg aktív</p>
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card warning">
                <h5>Inaktív ingatlanok</h5>
                <h2>{{ $stats['inactive_properties'] }}</h2>
                <p><i class="fas fa-pause-circle me-2"></i>Jelenleg inaktív</p>
                <i class="fas fa-pause-circle"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card info">
                <h5>Saját ingatlanok</h5>
                <h2>{{ $stats['sajat_properties'] }}</h2>
                <p><i class="fas fa-user me-2"></i>Saját ingatlanok</p>
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>
    
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stats-card info">
                <h5>Új ingatlanok</h5>
                <h2>{{ $stats['new_properties_last_7_days'] }}</h2>
                <p><i class="fas fa-plus-circle me-2"></i>Az elmúlt 7 napban</p>
                <i class="fas fa-plus-circle"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h5>Összes vevő</h5>
                <h2>{{ $stats['total_customers'] }}</h2>
                <p><i class="fas fa-users me-2"></i>Az adatbázisban</p>
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card success">
                <h5>Összes projekt</h5>
                <h2>{{ $stats['total_projects'] }}</h2>
                <p><i class="fas fa-project-diagram me-2"></i>Projektek</p>
                <i class="fas fa-project-diagram"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card warning">
                <h5>Összes partner</h5>
                <h2>{{ $stats['total_partners'] }}</h2>
                <p><i class="fas fa-handshake me-2"></i>Partnerek</p>
                <i class="fas fa-handshake"></i>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Gyors műveletek</h4>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('admin.properties.create') }}" class="btn btn-primary w-100">
                                <i class="fas fa-plus me-2"></i>Új ingatlan
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-tools me-2"></i>Ingatlan karbantartás
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.customers.create') }}" class="btn btn-success w-100">
                                <i class="fas fa-user-plus me-2"></i>Új vevő
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-user-cog me-2"></i>Vevő karbantartás
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Property Search -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Ingatlan/Projekt keresése</h4>
                </div>
                <div class="card-body">
                    <div class="search-bar">
                        <input type="text" id="property-search" class="form-control" placeholder="Keresés ingatlan kód vagy cím alapján...">
                        <i class="fas fa-search"></i>
                        <div id="property-dropdown" class="property-dropdown" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Ingatlanok havi bontásban</h5>
                </div>
                <div class="card-body">
                    <canvas id="propertyChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Új vevők alakulása</h5>
                </div>
                <div class="card-body">
                    <canvas id="buyerChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activities -->
    @if(isset($recentActivities) && $recentActivities->count() > 0)
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Legutóbbi események</h4>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($recentActivities as $activity)
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
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Users Overview -->
    @if(isset($users) && $users->count() > 0)
    <div class="row g-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Felhasználók áttekintése</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Felhasználó</th>
                                    <th>Ingatlanok</th>
                                    <th>Projektek</th>
                                    <th>Vevők</th>
                                    <th>Partnerek</th>
                                    <th>Összesen</th>
                                    <th>Regisztráció</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>
                                        <strong>{{ $user['name'] }}</strong><br>
                                        <small class="text-muted">{{ $user['email'] }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.properties.index', ['user_id' => $user['id']]) }}" 
                                           class="btn btn-sm {{ $user['properties_count'] > 0 ? 'btn-outline-primary' : 'btn-outline-secondary' }}"
                                           {{ $user['properties_count'] == 0 ? 'disabled' : '' }}>
                                            {{ $user['properties_count'] }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.projects.index', ['user_id' => $user['id']]) }}" 
                                           class="btn btn-sm {{ $user['projects_count'] > 0 ? 'btn-outline-info' : 'btn-outline-secondary' }}"
                                           {{ $user['projects_count'] == 0 ? 'disabled' : '' }}>
                                            {{ $user['projects_count'] }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.customers.index', ['refId' => $user['id']]) }}" 
                                           class="btn btn-sm {{ $user['customers_count'] > 0 ? 'btn-outline-success' : 'btn-outline-secondary' }}"
                                           {{ $user['customers_count'] == 0 ? 'disabled' : '' }}>
                                            {{ $user['customers_count'] }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.partners.index', ['user_id' => $user['id']]) }}" 
                                           class="btn btn-sm {{ $user['partners_count'] > 0 ? 'btn-outline-warning' : 'btn-outline-secondary' }}"
                                           {{ $user['partners_count'] == 0 ? 'disabled' : '' }}>
                                            {{ $user['partners_count'] }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $user['total_count'] }}</span>
                                    </td>
                                    <td>
                                        <small>{{ $user['created_at']->format('Y-m-d') }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
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
    
    .property-code {
        font-weight: bold;
        color: var(--primary-color);
    }
    
    .property-title {
        color: #666;
        font-size: 0.9em;
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
        background: linear-gradient(180deg, #2563eb, #3b82f6);
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
        background: var(--primary-color);
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        text-align: center;
        line-height: 30px;
        font-weight: bold;
        box-shadow: 0 0 8px rgba(37, 99, 235, 0.7);
    }
    
    .timeline-content {
        background: #fff;
        padding: 1rem 1.25rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 8px rgba(37, 99, 235, 0.15);
        transition: box-shadow 0.3s ease;
    }
    
    .timeline-content:hover {
        box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3);
    }
    
    .timeline-time {
        font-size: 0.85rem;
        color: #666;
        margin-top: 5px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Property search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('property-search');
        const dropdown = document.getElementById('property-dropdown');
        let searchTimeout;
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    dropdown.style.display = 'none';
                    return;
                }
                
                searchTimeout = setTimeout(() => {
                    fetch(`/admin/api/property-search?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.length === 0) {
                                dropdown.innerHTML = '<div class="property-dropdown-item">Nincs találat</div>';
                                dropdown.style.display = 'block';
                                return;
                            }
                            
                            dropdown.innerHTML = data.map(property => {
                                return `
                                    <div class="property-dropdown-item" onclick="window.location.href='/admin/properties/${property.id}'">
                                        <div class="property-code">${property.property_code}</div>
                                        <div class="property-title">${property.title}</div>
                                    </div>
                                `;
                            }).join('');
                            dropdown.style.display = 'block';
                        })
                        .catch(error => {
                            console.error('Search error:', error);
                        });
                }, 300);
            });
            
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });
        }
        
        // Charts
        if (document.getElementById('propertyChart')) {
            new Chart(document.getElementById('propertyChart'), {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Már', 'Ápr', 'Máj', 'Jún'],
                    datasets: [{
                        label: 'Ingatlanok',
                        data: [12, 19, 8, 15, 22, 30],
                        backgroundColor: 'rgba(37, 99, 235, 0.7)',
                        borderColor: 'rgba(37, 99, 235, 1)',
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
        }
        
        if (document.getElementById('buyerChart')) {
            new Chart(document.getElementById('buyerChart'), {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Már', 'Ápr', 'Máj', 'Jún'],
                    datasets: [{
                        label: 'Új vevők',
                        data: [5, 9, 7, 14, 10, 16],
                        fill: false,
                        borderColor: 'rgba(16, 185, 129, 1)',
                        backgroundColor: 'rgba(16, 185, 129, 0.3)',
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
        }
    });
</script>
@endpush
@endsection

