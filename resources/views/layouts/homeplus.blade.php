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
    <title>OtthonPlusz Ingatlaniroda</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="56x56" href="{{asset("/images/fav-icon/icon.png")}}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset("css/bootstrap.min.css")}}" media="all">
    <!-- Main style sheet -->
    <link rel="stylesheet" type="text/css" href="{{asset("css/style.css")}}" media="all">
    <!-- responsive style sheet -->
    <link rel="stylesheet" type="text/css" href="{{asset("css/responsive.css")}}" media="all">
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
            navText:["<div class='nav-btn prev-slide'><i class='prev-slide fa-thin fa-angle-left fa-5x'></i></div>","<div class='nav-btn next-slide'><i class='next-slide fa-thin fa-angle-right fa-5x'></i></div>"],
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

@once
    @stack('javascript')
@endonce
</body>

</html>
