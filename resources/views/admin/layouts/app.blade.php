<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'OtthonPlusz') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons.css') }}">
    
    <!-- Styles -->
    @vite(['resources/css/app.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    @stack('styles')
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
                    <i class="fas fa-home"></i>
                    <span>OtthonPlusz</span>
                </a>
                <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <nav class="sidebar-nav">
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.properties.index') }}" class="nav-link {{ request()->routeIs('admin.properties.*') ? 'active' : '' }}">
                            <i class="fas fa-building"></i>
                            <span>Ingatlanok</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.projects.index') }}" class="nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                            <i class="fas fa-project-diagram"></i>
                            <span>Projektek</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <span>Vevők</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.partners.index') }}" class="nav-link {{ request()->routeIs('admin.partners.*') ? 'active' : '' }}">
                            <i class="fas fa-handshake"></i>
                            <span>Partnerek</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.clients.index') }}" class="nav-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                            <i class="fas fa-user-tie"></i>
                            <span>Megbízók</span>
                        </a>
                    </li>
                    
                    <li class="nav-item has-submenu">
                        <a href="#" class="nav-link">
                            <i class="fas fa-cog"></i>
                            <span>Beállítások</span>
                            <i class="fas fa-chevron-down submenu-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{ route('admin.users.index') }}" class="submenu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="fas fa-user"></i> Felhasználók
                            </a></li>
                            <li><a href="{{ route('admin.labels.index') }}" class="submenu-link {{ request()->routeIs('admin.labels.*') ? 'active' : '' }}">
                                <i class="fas fa-tags"></i> Címkék
                            </a></li>
                            <li><a href="{{ route('admin.property-types.index') }}" class="submenu-link {{ request()->routeIs('admin.property-types.*') ? 'active' : '' }}">
                                <i class="fas fa-home"></i> Ingatlan típusok
                            </a></li>
                            <li><a href="{{ route('admin.property-subtypes.index') }}" class="submenu-link {{ request()->routeIs('admin.property-subtypes.*') ? 'active' : '' }}">
                                <i class="fas fa-home"></i> Ingatlan altípusok
                            </a></li>
                            <li><a href="{{ route('admin.settlements.index') }}" class="submenu-link {{ request()->routeIs('admin.settlements.*') ? 'active' : '' }}">
                                <i class="fas fa-map-marker-alt"></i> Települések
                            </a></li>
                            <li><a href="{{ route('admin.settlement-parts.index') }}" class="submenu-link {{ request()->routeIs('admin.settlement-parts.*') ? 'active' : '' }}">
                                <i class="fas fa-map"></i> Településrészek
                            </a></li>
                            <li><a href="{{ route('admin.settlement-groups.index') }}" class="submenu-link {{ request()->routeIs('admin.settlement-groups.*') ? 'active' : '' }}">
                                <i class="fas fa-layer-group"></i> Település csoportok
                            </a></li>
                            <li><a href="{{ route('admin.property-attributes.index') }}" class="submenu-link {{ request()->routeIs('admin.property-attributes.*') ? 'active' : '' }}">
                                <i class="fas fa-list"></i> Ingatlan attribútumok
                            </a></li>
                            <li><a href="{{ route('admin.property-attribute-categories.index') }}" class="submenu-link {{ request()->routeIs('admin.property-attribute-categories.*') ? 'active' : '' }}">
                                <i class="fas fa-folder"></i> Attribútum kategóriák
                            </a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item has-submenu">
                        <a href="#" class="nav-link">
                            <i class="fas fa-file-alt"></i>
                            <span>Tartalom</span>
                            <i class="fas fa-chevron-down submenu-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{ route('admin.services.index') }}" class="submenu-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                                <i class="fas fa-concierge-bell"></i> Szolgáltatások
                            </a></li>
                            <li><a href="{{ route('admin.service-categories.index') }}" class="submenu-link {{ request()->routeIs('admin.service-categories.*') ? 'active' : '' }}">
                                <i class="fas fa-folder"></i> Szolgáltatás kategóriák
                            </a></li>
                            <li><a href="{{ route('admin.information.index') }}" class="submenu-link {{ request()->routeIs('admin.information.*') ? 'active' : '' }}">
                                <i class="fas fa-info-circle"></i> Információk
                            </a></li>
                            <li><a href="{{ route('admin.information-categories.index') }}" class="submenu-link {{ request()->routeIs('admin.information-categories.*') ? 'active' : '' }}">
                                <i class="fas fa-folder"></i> Információ kategóriák
                            </a></li>
                            <li><a href="{{ route('admin.static-pages.index') }}" class="submenu-link {{ request()->routeIs('admin.static-pages.*') ? 'active' : '' }}">
                                <i class="fas fa-file"></i> Statikus oldalak
                            </a></li>
                            <li><a href="{{ route('admin.slider-images.index') }}" class="submenu-link {{ request()->routeIs('admin.slider-images.*') ? 'active' : '' }}">
                                <i class="fas fa-images"></i> Slider képek
                            </a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.customer-searches.index') }}" class="nav-link {{ request()->routeIs('admin.customer-searches.*') ? 'active' : '' }}">
                            <i class="fas fa-search"></i>
                            <span>Vevő keresések</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.offers.index') }}" class="nav-link {{ request()->routeIs('admin.offers.*') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span>Ajánlatok</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="admin-main">
            <!-- Top Header -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="sidebar-toggle-mobile d-lg-none" id="sidebarToggleMobile">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                </div>
                
                <div class="header-right">
                    <div class="user-menu">
                        <div class="user-info">
                            <span class="user-name">{{ Auth::user()->name ?? 'Admin' }}</span>
                            <span class="user-email">{{ Auth::user()->email ?? '' }}</span>
                        </div>
                        <div class="user-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="user-dropdown">
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-user"></i> Profil
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-cog"></i> Beállítások
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
                                @csrf
                            </form>
                            <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> Kijelentkezés
                            </a>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content Area -->
            <main class="admin-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    @vite(['resources/js/app.js'])
    <script src="{{ asset('js/admin.js') }}"></script>
    
    @stack('scripts')
</body>
</html>

