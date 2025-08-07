@extends(backpack_view('blank'))

@php
    $user = \Illuminate\Support\Facades\Auth::user();
    Widget::add([
        'type'        => 'jumbotron',
        'heading'     => "Üdvözöllek $user->name!",
        'heading_class' => 'display-3 '.(backpack_theme_config('layout') === 'horizontal_overlap' ? ' text-white' : ''),
        'content'     => '',
        'content_class' => backpack_theme_config('layout') === 'horizontal_overlap' ? 'text-white' : '',
        'button_link' => backpack_url('logout'),
        'button_text' => trans('Kejelentkezés'),
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
                            <input type="text" id="property-search" class="form-control search-input" placeholder="Keresés...">
                            <i class="fas fa-search search-icon"></i>
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
                        <a href="#" class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-user-plus action-icon"></i> Új vevő felvitele
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card action-card">
                    <div class="card-body p-3">
                        <a href="#" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
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
            <div class="col-md-4">
                <div class="card text-white bg-primary shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Összes ingatlan</h5>
                        <h2 class="card-text">126</h2>
                        <p class="mb-0"><i class="fas fa-home me-2"></i>Frissítve: ma</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Új vevők</h5>
                        <h2 class="card-text">24</h2>
                        <p class="mb-0"><i class="fas fa-user-plus me-2"></i>Az elmúlt 7 napban</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-secondary shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Feltöltött képek</h5>
                        <h2 class="card-text">532</h2>
                        <p class="mb-0"><i class="fas fa-image me-2"></i>Az adatbázisban</p>
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

@endsection


