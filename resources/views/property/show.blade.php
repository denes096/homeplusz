@extends('layouts/homeplus')

@section('content')

        <!-- A Kereső sáv marad, de a bg nem kell! -->
    <!--
		=====================================================
			Property Listing Details
		=====================================================
		-->
    <div class="listing-details-one theme-details-one bg-white pt-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="labels-on-show">
                        @foreach($property->labels as $label)
                            <div class="label-on-details" style="background-color: {{ $label->color }}; color: white; font-size:14px !important;"><strong>{{ $label->name }}</strong></div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-6">
                    <h5 class="property-titlee" style="font-size:16px !important;">{{$property->settlement?->name}} {{$property->settlementPart?->name}}</h5>
                    <div class="d-flex flex-wrap mt-10">
                        <div class="list-type text-uppercase border-20 mt-15 me-3">{{$property->getAdType() }}</div>
                        <div class="address mt-15"><i class="bi bi-geo-alt"></i> CÍM:</div> <!-- Csak a belépett dolgozó láthatja a pontos címet! -->
                    </div>
                </div>
                <div class="col-lg-6 text-lg-end">
                    <div class="d-inline-block md-mt-40">
                        <div class="price color-dark fw-bold" style="font-size:20px !important;">ÁR: {{$property->price }}M FT.</div>
                        <ul class="style-none d-flex align-items-center action-btns">
                            <li class="me-auto fw-500 color-dark"><i class="fa-sharp fa-regular fa-share-nodes me-2"></i> Megosztás</li>
                            <li><a href="#" class="d-flex align-items-center justify-content-center rounded-circle tran3s" style="width: 30px; height:30px;"><i class="fa-light fa-heart"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="media-gallery pt-4">
                <div id="media_slider" class="carousel slide row"
                    style="max-height: 500px; overflow: auto"
                >
                    <div class="col-lg-10">
                        <div class="bg-white shadow4 border-20 p-3">
                            <div class="position-relative z-1 overflow-hidden border-20">
                                <div class="img-fancy-btn border-10 fw-500 fs-16 color-dark">
                                    {{ count($property->getImageUrls()) }} Kép<i class="fa-regular fa-image"></i>
                                    @foreach($property->getImageUrls() as $url)
                                        <a href="{{ $url }}" class="d-block" data-fancybox="{{ $property->id }}" data-caption="{{ $property->id }}"></a>
                                    @endforeach
                                </div>

                                <!-- IDE KERÜL A carousel-indicators -->
                                <div class="carousel-indicators">
                                    @foreach($property->getImageUrls() as $url)
                                        <button type="button" data-bs-target="#media_slider" data-bs-slide-to="{{ $loop->index }}"
                                                aria-label="Slide {{ $loop->index + 1 }}"
                                            {{ $loop->index == 0 ? 'class=active aria-current=true' : '' }}>
                                        </button>
                                    @endforeach
                                </div>

                                <div class="carousel-inner">
                                    @foreach($property->getImageUrls() as $url)
                                        <div class="carousel-item {{ $loop->index == 0 ? 'active' : '' }}">
                                            <img src="{{ $url }}" alt="" class="border-20 w-50">
                                        </div>
                                    @endforeach
                                </div>

                                <button class="carousel-control-prev" type="button" data-bs-target="#media_slider" data-bs-slide="prev">
                                    <i class="bi bi-chevron-left"></i>
                                    <span class="visually-hidden">Vissza</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#media_slider" data-bs-slide="next">
                                    <i class="bi bi-chevron-right"></i>
                                    <span class="visually-hidden">Következő</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Thumbnailokat külön is megjelenítheted, de ne nevezd carousel-indicators-nak -->
                    <div class="col-lg-2" style="max-height: 500px; overflow:auto;">
                        <div class="border-15 bg-white shadow4 p-15 w-100 h-100">
                            @foreach($property->getImageUrls() as $url)
                                <button type="button" data-bs-target="#media_slider" data-bs-slide-to="{{ $loop->index }}" class="d-block mb-2">
                                    <img src="{{ $url }}" alt="" class="border-10 w-100">
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
            <div class="property-feature-list bg-white shadow4 border-20 p-40 mt-50 mb-60">
                <h4 class="sub-title-one mb-4 fs-5">Ingatlan adatai</h4>
                <ul class="style-none d-flex flex-wrap align-items-center justify-content-between">
                    <li>
                        <img src="/images/lazy.svg" data-src="/images/icon/icon_47.svg" alt="" class="lazy-img icon">
                        <span class="fs-20 color-dark">{{ $property->attributes->firstWhere('name', 'epulet_lakotermeret')->pivot->value }}m2</span>
                    </li>
                    <li>
                        <img src="/images/lazy.svg" data-src="/images/icon/icon_48.svg" alt="" class="lazy-img icon">
                        <span class="fs-20 color-dark">{{ $property->attributes->firstWhere('name', 'epulet_szobaszam')->pivot->value }}</span>
                    </li>
                    <li>
                        <img src="/images/lazy.svg" data-src="/images/icon/icon_49.svg" alt="" class="lazy-img icon">
                        <span class="fs-20 color-dark">
                            2
                        </span>
                    </li>
                    <li>
                        <img src="/images/lazy.svg" data-src="/images/icon/icon_53.svg" alt="garázs" class="lazy-img icon">
                        <span class="fs-20 color-dark">1</span>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-xl-8">
                    <div class="property-overview bg-white shadow4 border-20 p-40 mb-50">
                        <h4 class="mb-4 fs-5">Részletes ingatlan leírás</h4>
                        <p class="fs-20 lh-lg">{!! $property->description !!}</p>
                    </div>


                    <div class="similar-property">
                        <h4 class="mb-4 fs-5">Hasonló ingatlanok</h4>
                        <div class="similar-listing-slider-one">
                            <div class="item">
                                <div class="listing-card-one shadow4 style-three border-30 mb-50">
                                    <div class="img-gallery p-15">
                                        <div class="position-relative border-20 overflow-hidden">
                                            <div class="tag bg-white text-dark fw-500 border-20">ELADÓ</div>
                                            <img src="/images/listing/img_13.jpg" class="w-100 border-20" alt="...">
                                            <a href="listing_details_06.html" class="btn-four inverse rounded-circle position-absolute"><i class="bi bi-arrow-up-right"></i></a>
                                            <div class="img-slider-btn">
                                                03 <i class="fa-regular fa-image"></i>
                                                <a href="/images/listing/img_large_01.jpg" class="d-block" data-fancybox="img1" data-caption="ÉRD"></a>
                                                <a href="/images/listing/img_large_02.jpg" class="d-block" data-fancybox="img1" data-caption="ÉRD"></a>
                                                <a href="/images/listing/img_large_03.jpg" class="d-block" data-fancybox="img1" data-caption="ÉRD"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="property-info pe-4 ps-4"> <!-- Hasonló ingatlan tulajdonságokat megadtam, azokat kellene itt megjeleníteni -->
                                        <a href="listing_01.html" class="title tran3s">Eladó Családi ház</a>
                                        <div class="address">Érd Tusculanum</div>
                                        <ul class="style-none feature d-flex flex-wrap align-items-center justify-content-between">
                                            <li class="d-flex align-items-center">
                                                <span class="fs-16"><strong class="fw-500 color-dark">334</strong>㎡</span>
                                            </li>
                                            <li class="d-flex align-items-center">
                                                <span class="fs-16"><strong class="fw-500 color-dark">10</strong> szoba</span>
                                            </li>
                                            <li class="d-flex align-items-center">
                                                <span class="fs-16"><strong class="fw-500 color-dark">02</strong> fürdőszoba</span>
                                            </li>
                                        </ul>
                                        <div class="pl-footer top-border d-flex align-items-center justify-content-between">
                                            <strong class="price fw-500 color-dark">157,70M Ft.</strong>
                                            <ul class="style-none d-flex action-icons">
                                                <li><a href="#"><i class="fa-light fa-heart"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                            </div>
                            <div class="item">
                            </div>
                            <div class="item">
                            </div>
                            <div class="item">
                            </div>
                        </div>
                    </div>

                    <div class="property-score bg-white shadow4 border-20 p-40 mb-50">

                    </div>

                    <div class="property-location mb-50">
                        <div class="bg-white shadow4 border-20 p-30">
                            <div class="map-banner overflow-hidden border-15">
                                <div class="gmap_canvas h-100 w-100">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d83088.3595592641!2d-105.54557276330914!3d39.29302101722867!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x874014749b1856b7%3A0xc75483314990a7ff!2sColorado%2C%20USA!5e0!3m2!1sen!2sbd!4v1699764452737!5m2!1sen!2sbd" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="w-100 h-100"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="col-xl-4 col-lg-8 me-auto ms-auto">
                    <div class="theme-sidebar-one dot-bg p-30 ms-xxl-3 lg-mt-80">
                        <div class="agent-info bg-white border-20 p-30 mb-40">
                            <img src="{{ $property->user->getProfilePicture() }}" data-src="{{ $property->user->getProfilePicture() }}" alt="" class="lazy-img rounded-circle ms-auto me-auto mt-3 avatar">
                            <div class="text-center mt-25">
                                <h6 class="name">{{ $property->user->name }}</h6>
                                <p class="fs-16">{{ $property->user->position }}</p>
                                <ul class="style-none d-flex align-items-center justify-content-center social-icon">
                                    <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                                    <li><a href="#"><i class="fa-brands fa-linkedin"></i></a></li>
                                </ul>
                            </div>
                            <div class="divider-line mt-40 mb-45 pt-20">
                                <ul class="style-none">
                                    <li>Email: <span><a href="mailto:akabirr770@gmail.com">{{ $property->user->email }}</a></span></li>
                                    <li>Phone: <span><a href="tel:+12347687565">+36301112233</a></span></li>
                                </ul>
                            </div>
                            <div>
                                <h5>Kérjen személyes bemutatást!</h5>
                                <p>Adja meg adatait, és hogy mikor érne rá!</p>
                                <form action="#">
                                    <div class="input-box-three mb-25">
                                        <div class="label">Neve</div>
                                        <input type="text" placeholder="Teljes Neve" class="type-input">
                                    </div>

                                    <div class="input-box-three mb-25">
                                        <div class="label">E-mail címe</div>
                                        <input type="email" placeholder="E-mailcíme" class="type-input">
                                    </div>

                                    <div class="input-box-three mb-25">
                                        <div class="label">Telefonszáma</div>
                                        <input type="tel" placeholder="Telefonszáma" class="type-input">
                                    </div>

                                    <div class="input-box-three mb-15">
                                        <div class="label">Üzenet</div>
                                        <textarea placeholder="Üzenet..."></textarea>
                                    </div>

                                    <button class="btn-nine text-uppercase rounded-3 w-100 mb-10">Küldés</button>
                                </form>

                            </div>
                        </div>
                        <!-- /.agent-info -->

                        <div class="tour-schedule bg-white border-20 p-30 mb-40">
                            <h5 class="mb-40">Segítségre van szüksége?</h5>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
