<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="Ingatlan adás, vétel, bérlés">
    <meta name="description" content="Ön keres, Mi megtaláljuk">
    <meta property="og:site_name" content="Homy">
    <meta property="og:url" content="https://otthonplusz.hu">
    <meta property="og:type" content="website">
    <meta property="og:title" content="OtthonPlusz Ingatlaniroda">
    <meta name='og:image' content='images/assets/ogg.png'>
    <!-- For IE -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- For Resposive Device -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- For Window Tab Color -->
    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#0D1A1C">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#0D1A1C">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="#0D1A1C">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>OtthonPlusz Ingatlaniroda</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="56x56" href="{{asset("/images/fav-icon/icon.png")}}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset("css/bootstrap.min.css")}}" media="all">
    <!-- Main style sheet -->
    <link rel="stylesheet" type="text/css" href="{{asset("css/style.css")}}" media="all">
    <!-- responsive style sheet -->
    <link rel="stylesheet" type="text/css" href="{{asset("css/responsive.css")}}" media="all">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        ::-webkit-scrollbar {
            width: 10px;
            }

            /* Track */
            ::-webkit-scrollbar-track {
            box-shadow: inset 0 0 5px grey;
            border-radius: 6px;
            }

            /* Handle */
            ::-webkit-scrollbar-thumb {
            background: #000;
            border-radius: 6px;
            }

            /* Handle on hover */
            ::-webkit-scrollbar-thumb:hover {
            background:rgb(0, 0, 0);
            }
    </style>

    <style>
        .hover-dark:hover{
            background:rgb(255, 255, 255) !important;
        }
        .object-fit{
            object-fit: cover;
            height: 100%;
        }
        .slick-slide{
            height: 100% !important;
        }
        .listing-card-one{
            background-color: #f4f4f4 !important;
        }
        #dont-hide{
            color: #000;
        }
        .select2-container--default .select2-selection--multiple{
            border: 1px solid #000 !important;
            border-radius: 0.5rem !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice{
            background-color: #96006B !important;
            color: #fff !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove{
            color: #fff !important;
        }
        #dont-hide input {
            border: 1px solid #000 !important;
            border-radius: 0.5rem !important;
        }
        .select2-results__option{
            color: #000 !important;
        }
    </style>

    <!-- Fix Internet Explorer ______________________________________-->
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <script src="{{asset("vendor/html5shiv.js")}}"></script>
    <script src="{{asset("vendor/respond.js")}}"></script>
    <![endif]-->

    @once
        @stack('css')
    @endonce
</head>

<body>
<!-- ===================================================
    Loading Transition
==================================================== -->
<div id="preloader">
    <div id="ctn-preloader" class="ctn-preloader">
        <div class="icon"><img src="{{asset("/images/icon/otthonplusz_logo_txt.png")}}" alt="" class="m-auto d-block" width="64"></div>
    </div>
</div>


@section('sidenav')
<!--
=============================================
    Sidenav
==============================================
-->
@include('includes/sidenav')
@show

@section('main-menu')

<!--
=============================================
    Theme Main Menu
==============================================
-->
@include('includes/main-menu')
@show
<!-- /.theme-main-menu -->

<!--
=============================================
    Hero Banner
==============================================
-->
@include('includes/searchbar', [
    'settlements' => $settlements
])
@show
<!-- /.hero-banner -->

@yield('content')
<div class="information-1  w-100 p-5">
    <div class="container d-flex flex-wrap justify-content-center align-items-center">
        <div class="row col-sm-4 col-12 px-4">
            <div class="pt-3">
                <img src="/images/icon/calendar.png" alt="calendar">
            </div>
            <div>
                <h3 class="fs-24 pt-4 fw-bold text-uppercase" style="opacity: 40%;">Közvetítés</h3>
            </div>
            <div>
                <h1 class="text-white" style="font-weight: 400 !important;">Ingatlanközvetítői <br> tevékenység</h1>
            </div>
            <div>
                <a href="#"class="text-white fs-6 py-4" style="text-decoration: underline;">Tovább...</a>
            </div>
        </div>
        <div class="row col-sm-4 col-12 px-4">
            <div class="pt-3">
                <img src="/images/icon/bank.png" alt="calendar">
            </div>
            <div>
                <h3 class="fs-24 pt-4 fw-bold text-uppercase" style="opacity: 40%;">Hitel + Támogatás</h3>
            </div>
            <div>
                <h1 class="text-white" style="font-weight: 400 !important;">Hitelügyintézés <br> kiválasztása</h1>
            </div>
            <div>
                <a href="#"class="text-white fs-6 py-4" style="text-decoration: underline;">Tovább...</a>
            </div>
        </div>
        <div class="row col-sm-4 col-12 px-4">
            <div class="pt-3">
                <img src="/images/icon/chat.png" alt="calendar">
            </div>
            <div>
                <h3 class="fs-24 pt-4 fw-bold text-uppercase" style="opacity: 40%;">ügyintézés</h3>
            </div>
            <div>
                <h1 class="text-white" style="font-weight: 400 !important;">Tárgyalások <br> lebonyolítása</h1>
            </div>
            <div>
                <a href="#"class="text-white fs-6 py-4" style="text-decoration: underline;">Tovább...</a>
            </div>
        </div>
    </div>
</div>
<div class="information w-100 d-none d-sm-block">
    <img src="/images/assets/Group 408.jpeg" alt="" class="w-100">
</div>

<!--
=====================================================
    Footer
=====================================================
-->
@include('includes/footer')

<!-- ################### Login Modal ####################### -->
<!-- Modal -->
@include('includes/login-modal')


<button class="scroll-top">
    <i class="bi bi-arrow-up-short"></i>
</button>

<!-- Optional JavaScript _____________________________  -->

<!-- jQuery first, then Bootstrap JS -->
<!-- jQuery -->
<script src="{{asset("vendor/jquery.min.js")}}"></script>
<!-- Bootstrap JS -->
<script src="{{asset("vendor/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
<!-- WOW js -->
<script src="{{asset("vendor/wow/wow.min.js")}}"></script>
<!-- Slick Slider -->
<script src="{{asset("vendor/slick/slick.min.js")}}"></script>
<!-- Fancybox -->
<script src="{{asset("vendor/fancybox/fancybox.umd.js")}}"></script>
<!-- Lazy -->
<script src="{{asset("vendor/jquery.lazy.min.js")}}"></script>
<!-- js Counter -->
<script src="{{asset("vendor/jquery.counterup.min.js")}}"></script>
<script src="{{asset("vendor/jquery.waypoints.min.js")}}"></script>
<!-- Nice Select -->
<script src="{{asset("vendor/nice-select/jquery.nice-select.min.js")}}"></script>
<!-- validator js -->
<script src="{{asset("vendor/validator.js")}}"></script>

<!-- Theme js -->
<script src="{{asset("js/theme.js")}}"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="{{asset("js/main.js")}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        jQuery('.multiselect').select2({
            width: '100%'
        });

        $('.listing-slider-one-owl').owlCarousel({
            loop: true,
            margin: 20,
            nav: true,
            navText:["<div class='nav-btn prev-slide'><i class='prev-slide fa-thin fa-angle-left fa-4x'></i></div>","<div class='nav-btn next-slide'><i class='next-slide fa-thin fa-angle-right fa-4x'></i></div>"],
            dots: true,
            autoplay: true,
            autoplayTimeout: 5000,
            smartSpeed: 600,
            items: 1, // itt egyesével jön be
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                992: {
                    items: 3
                },
                1200: {
                    items: 4
                }
            }
        });
    });
    $('.listing-slider-one-static').owlCarousel({
        loop: false,
        margin: 20,
        nav: false,
        dots: false,
        autoplay: false,
        responsive: {
            0: {
                items: 1
            },
            768: {
                items: 2
            },
            992: {
                items: 3
            },
            1200: {
                items: 4
            }
        }
    })

    // Favorites functionality
    function loadFavorites() {
        const favorites = JSON.parse(localStorage.getItem('propertyFavorites') || '[]');
        
        // Update heart icons based on favorites
        document.querySelectorAll('.favorite-heart').forEach(heart => {
            const propertyId = parseInt(heart.dataset.propertyId);
            if (favorites.includes(propertyId)) {
                heart.classList.remove('fa-light');
                heart.classList.add('fa-solid');
                heart.style.color = '#96006B';
            } else {
                heart.classList.remove('fa-solid');
                heart.classList.add('fa-light');
                heart.style.color = '';
            }
        });
    }

    function toggleFavorite(propertyId) {
        let favorites = JSON.parse(localStorage.getItem('propertyFavorites') || '[]');
        const index = favorites.indexOf(propertyId);
        
        if (index > -1) {
            // Remove from favorites
            favorites.splice(index, 1);
        } else {
            // Add to favorites
            favorites.push(propertyId);
        }
        
        localStorage.setItem('propertyFavorites', JSON.stringify(favorites));
        loadFavorites();
    }

    // Simple favorites functionality
    function initFavorites() {
        // Load existing favorites
        loadFavorites();
        
        // Add click handlers to all heart icons
        const heartIcons = document.querySelectorAll('.favorite-heart');
        
        heartIcons.forEach(function(heart) {
            // Remove any existing listeners to prevent duplicates
            if (heart.clickHandler) {
                heart.removeEventListener('click', heart.clickHandler);
            }
            
            // Create new click handler
            heart.clickHandler = function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const propertyId = parseInt(heart.dataset.propertyId);
                toggleFavorite(propertyId);
            };
            
            // Add the event listener
            heart.addEventListener('click', heart.clickHandler);
        });
    }


    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initFavorites();
            
            // If we're on the favorites page, load the properties
            if (window.location.pathname.includes('/kedvenceim')) {
                loadFavoritesPage();
            }
        });
    } else {
        initFavorites();
        
        // If we're on the favorites page, load the properties
        if (window.location.pathname.includes('/kedvenceim')) {
            loadFavoritesPage();
        }
    }
    
    // Function to load favorites page
    function loadFavoritesPage() {
        // Load favorites from localStorage and send to server
        const favorites = JSON.parse(localStorage.getItem('propertyFavorites') || '[]');
        
        if (favorites.length > 0) {
            // Send favorites to server to get property data
            fetch('/kedvenceim', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ favorites: favorites })
            })
            .then(response => response.text())
            .then(html => {
                // Replace the content with the updated properties
                const container = document.querySelector('#favorites-container');
                if (container) {
                    container.innerHTML = html;
                    
                    // Re-initialize favorites for the new content
                    if (typeof initFavorites === 'function') {
                        initFavorites();
                    }
                }
            })
            .catch(error => {
                console.error('Error loading favorites:', error);
            });
        }
    }
</script>

<style>
    .owl-carousel .nav-btn{
        height: 47px;
        position: absolute;
        width: 26px;
        cursor: pointer;
        top: 100px !important;
    }

    .carousel-control-prev-icon, .carousel-control-nex-icon {
        display: block;
    }
    .owl-carousel .owl-prev.disabled,
    .owl-carousel .owl-next.disabled{
        pointer-events: none;
        opacity: 0.2;
    }

    .owl-carousel .prev-slide{
        left: -50px;
        height: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .owl-carousel .next-slide{
        right: -50px;
        height: 50%;
        display: flex;
        align-items: center;
        justify-content: center;

    }
    .owl-carousel .prev-slide:hover{
        color: black;
    }
    .owl-carousel .next-slide:hover{
        color: black;
    }

</style>

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

@once
    @stack('javascript')
@endonce
</body>

</html>
