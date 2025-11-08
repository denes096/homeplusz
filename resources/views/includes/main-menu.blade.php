@inject('services', 'App\Services\ServiceService')
@inject('infos', 'App\Services\InformationService')

<header class="theme-main-menu menu-overlay menu-style-three sticky-menu">
    <div class="d-flex pt-3 px-2">
        <!-- logo -->
        <div class="logo">
            <a href="/">
                <img src="/images/icon/otthonplusz_logo_txt.png" style="width: 50%" alt="OtthonPlusz">
            </a>
        </div>
        <div class="right-widget ms-auto me-3 me-lg-0 order-lg-3">
            <ul class="d-flex align-items-center style-none">
                    <li class="d-none d-md-inline-block ms-3 ms-xl-4 me-xl-4 w-85">
                        <a href="{{ route('property.favorites') }}" class="btn-ten" style="line-height: 40px;"><span>Kedvenceim</span><i class="fa-light fa-heart" style="color: #96006B;"></i></a>
                    </li>
                    @if (Auth::guest())
                    <li class="d-flex align-items-center login-btn-one">

                        <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="fw-500 tran3s"><i class="bi bi-person-circle" style="font-size: 30px; color: #96006B;"></i></a>
                    </li>
                    @else

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <a class="fw-500 tran3s" href="{{ url("logout") }}"
                                            onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                {{ __('Kijelentkezés') }}
                            </a>
                        </form>
                    @endif
            </ul>
        </div>
    </div>
    <div class="inner-content">
        <div class="top-header position-relative ps-1">
            <div class="d-flex align-items-center">
                <nav class="navbar navbar-expand-lg p0 ms-lg-3 order-lg-2 w-100">
                    <button class="navbar-toggler d-block d-lg-none" style="z-index: 100000000000000000000000000000000 !important;" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                            aria-label="Toggle navigation">
                        <span></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav align-items-lg-center">
                            <li class="d-flex justify-content-center align-items-center d-lg-none"><div class="logo"><a href="index.html" class="d-block"><img src="/images/icon/logotxt.png" alt=""></a></div></li>

                            @hasanyrole('super-admin|admin|referens')
                            <li class="nav-item dashboard-menu">
                                <a class="nav-link" href="{{backpack_url('dashboard')}}" target="_blank">Dashboard (csak dolgozók látják)</a>
                            </li>
                            @endhasanyrole

                            <li class="nav-item dropdown">
                                <div class="input-group">

                                    <input type="text" style="border-right:none;" name="code" id="search-code" class="form-control" placeholder="Ingatlan kód / referens" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                    <span class="input-group-text" id="basic-addon2" style="background-color:rgba(255, 255, 255, 0); color:#fff;"><button type="button" class="search-for-code"><i class="bi bi-arrow-right-circle" style="font-size: 20px; color: #96006B; border-left: none;"></i></button></span>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a href="/bemutatkozas" class="nav-link"><span>Bemutatkozás</span></a>
                            </li>
                            <li class="nav-item dropdown mega-dropdown-sm">
                                <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">Szolgáltatások
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="d-flex flex-column gx-1">

                                            @foreach($services->serviceCategories() as $serviceCategory)
                                                @if($serviceCategory->services->count() > 0)
                                                    <div class="col-lg-4">
                                                        <div class="menu-column">
                                                            <h6 class="mega-menu-title">{{$serviceCategory->name}}</h6>
                                                            <ul class="style-none mega-dropdown-list">
                                                                @foreach($serviceCategory->services as $service)
                                                                    <li><a href="/szolgaltatasok/{{$service->id}}-{{\Illuminate\Support\Str::slug($service->name)}}" class="dropdown-item">
                                                                        <span>{{$service->name}}</span>
                                                                    </a></li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                            <!--/.menu-column -->
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown mega-dropdown-sm">
                                <a href="#" class="nav-link"><span>Hasznos Informácók</span></a>
                                <ul class="dropdown-menu">
                                    <li class="d-flex flex-column gx-1">
                                        @foreach($infos->informationCategories() as $informationCategory)
                                            @if($informationCategory->informations->count() > 0)
                                                <div class="col-lg-4">
                                                    <div class="menu-column">
                                                        <h6 class="mega-menu-title">{{$informationCategory->name}}</h6>
                                                        <ul class="style-none mega-dropdown-list">
                                                            @foreach($informationCategory->informations as $information)
                                                                <li><a href="/informaciok/{{$information->id}}-{{\Illuminate\Support\Str::slug($information->name)}}" class="dropdown-item"><span>{{$information->name}}</span></a></li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link" href="#" role="button"  aria-expanded="false">
                                    Otthon <span style="color: #96006B;">magazin</span>
                                </a>
                            </li>
                            <li class="nav-item dropdown mega-dropdown-sm">
                                <a href="contact.html" class="nav-link"><span>Kapcsolat</span></a>
                                <ul class="dropdown-menu">
                                    <li class="row gx-1">
                                        <div class="col-lg-4">
                                            <div class="address-block mt-50">
                                                <h4 class="title pb-15 fs-6">Elérhetőségünk</h4>
                                                <p style="font-size: 14px !important;">2030 Érd<br>Riminyáki út 20.</p>
                                                <p style="font-size: 14px !important;">Írjon nekünk: <br><a href="mailto:otthonplusz@otthonplusz.hu" class="fw-bold">otthonplusz@otthonplusz.hu</a></p>
                                                <p style="font-size: 14px !important;">vagy hívjon: <br><a href="tel:310.841.5500" class="fw-bold">+36301234567</a></p>
                                            </div>
                                            <div class="menu-column d-none">
                                                <h6 class="mega-menu-title">Felirat?</h6>
                                                <ul class="style-none mega-dropdown-list">
                                                    <li><a href="about_us_01.html" class="dropdown-item"><span>Rólunk</span></a></li>
                                                </ul>
                                            </div> <!--/.menu-column -->
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="nav-item">
                        <a href="contact_us.html" class="nav-link joinIN px-3" style="color: #96006B; font-weight: 600;"><span>Csatlakozz hozzánk!</span></a>
                    </div>
                </nav>
            </div>
        </div> <!--/.top-header-->
    </div> <!-- /.inner-content -->
</header>

<style>
    .theme-main-menu .nav-item .nav-link{
        margin: 0 5px;
        font-size: 16px;
    }

</style>