@extends('layouts/homeplus')

@section('content')

        <!-- A Kereső sáv marad, de a bg nem kell! -->
    <!--
		=====================================================
			Property Listing Details
		=====================================================
		-->
    <div class="listing-details-one theme-details-one bg-white">
        <div class="container">
            <div class="row">
                <div class="p-3 col-12">
                    <h5>Rövidleírás</h5> <!-- TITLE -->
                </div>
                <div class="col-lg-7 d-flex">
                    <div class="d-flex flex-wrap mt-10 align-items-center">
                        <div class="address mt-15 d-flex align-items-center pe-4"><i class="bi bi-geo-alt"></i><h5 class="property-titlee ps-1 m-0" style="font-size:20px !important;">{{$property->settlement?->name}} {{$property->settlementPart?->name}}</h5></div>
                        <div class="text-uppercase border-1 roundes-pill mt-15 mb-0 ms-0 me-3"><p class="m-0" styele="font-size: 16px;">ID: {{$property->property_code}}</p></div> <!-- hiányos-->
                        <div class="list-type text-uppercase border-20 mt-15 me-3">{{$property->getAdType() }}</div>
                        <div class="labels-on-show d-flex align-items-center mt-15">
                            @foreach($property->labels as $label)
                                <div class="label-on-details" style="background-color: {{ $label->color }}; color: white; font-size:14px !important;"><strong>{{ $label->name }}</strong></div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 text-lg-end">
                    <div class="d-inline-block md-mt-40">
                        <div class="price color-dark fw-500" style="font-size:20px !important;">ÁR: {{$property->getShortPrice() }}</div>
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
            <div class="property-feature-list bg-white shadow4 border-20 p-40 my-5">
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
                    <div class="property-overview bg-white shadow4 border-20 py-4" style="font-size: 0.8rem !important;">
                        <h4 class="mb-4 fs-5">Részletes ingatlan leírás</h4>
                        <p>{!! $property->description !!}</p>
                    </div>


                    @if($similarProperties->count() > 0)
                    <div class="similar-property">
                        <h4 class="mb-4 fs-5">Hasonló ingatlanok</h4>
                        <div class="similar-listing-slider-one">
                            @foreach($similarProperties as $similarProperty)
                            <div class="item">
                                <div class="listing-card-one shadow4 style-three border-30 mb-50">
                                    <div class="img-gallery p-15">
                                        <div class="position-relative border-20 overflow-hidden">
                                            <div class="tag bg-white text-dark fw-500 border-20">{{ $similarProperty->getAdType() }}</div>
                                            <img src="{{ $similarProperty->getImageUrls()[0] ?? '/images/defaultProperty.png' }}" class="w-100 border-20" alt="{{ $similarProperty->title }}">
                                            <a href="{{ route('property.show', $similarProperty->id) }}" class="btn-four inverse rounded-circle position-absolute"><i class="bi bi-arrow-up-right"></i></a>
                                            <div class="img-slider-btn">
                                                {{ count($similarProperty->getImageUrls()) }} <i class="fa-regular fa-image"></i>
                                                @foreach($similarProperty->getImageUrls() as $url)
                                                    <a href="{{ $url }}" class="d-block" data-fancybox="similar{{ $similarProperty->id }}" data-caption="{{ $similarProperty->title }}"></a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="property-info pe-4 ps-4">
                                        <a href="{{ route('property.show', $similarProperty->id) }}" class="title tran3s">{{ ucfirst($similarProperty->getAdType()) . " " . $similarProperty->propertyType->name }}</a>
                                        <div class="address">{{ $similarProperty->getFullAddress() }}</div>
                                        <ul class="style-none feature d-flex flex-wrap align-items-center justify-content-between">
                                            <li class="d-flex align-items-center">
                                                <span class="fs-16"><strong class="fw-500 color-dark">{{ $similarProperty->attributes->firstWhere('name', 'epulet_lakotermeret')->pivot->value ?? 'N/A' }}</strong>㎡</span>
                                            </li>
                                            <li class="d-flex align-items-center">
                                                <span class="fs-16"><strong class="fw-500 color-dark">{{ $similarProperty->attributes->firstWhere('name', 'epulet_szobaszam')->pivot->value ?? 'N/A' }}</strong> szoba</span>
                                            </li>
                                            <li class="d-flex align-items-center">
                                                <span class="fs-16"><strong class="fw-500 color-dark">{{ $similarProperty->attributes->firstWhere('name', 'area')->pivot->value ?? '- ' }}</strong>m²</span>
                                            </li>
                                        </ul>
                                        <div class="pl-footer top-border d-flex align-items-center justify-content-between">
                                            <strong class="price fw-500 color-dark">{{ $similarProperty->formatHUFMillions(1) }}</strong>
                                            <ul class="style-none d-flex action-icons">
                                                <li><a href="#"><i class="fa-light fa-heart"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="property-score bg-white shadow4 border-20 p-40 mb-50">

                    </div>

                    <div class="property-location mb-50">
                        <div class="bg-white shadow4 border-20 p-30">
                            <h4 class="mb-4 fs-5">Ingatlan helye</h4>
                            <div class="map-banner overflow-hidden border-15">
                                <div class="gmap_canvas h-100 w-100">
                                    @if($property->hasCoordinates())
                                        <div id="property-map" style="height: 450px; width: 100%;"></div>
                                    @else
                                        <div class="d-flex align-items-center justify-content-center" style="height: 450px; background-color: #f8f9fa;">
                                            <div class="text-center">
                                                <i class="bi bi-geo-alt" style="font-size: 3rem; color: #6c757d;"></i>
                                                <p class="mt-3 text-muted">A térkép koordinátái nem állnak rendelkezésre</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @if($property->address)
                                <div class="mt-3">
                                    <p class="mb-1"><strong>Cím:</strong> {{ $property->address }}</p>
                                </div>
                            @endif
                        </div>
                    </div>


                </div>
                <div class="col-xl-4 col-lg-8 me-auto ms-auto">
                    <div class="theme-sidebar-one dot-bg p-30 ms-xxl-3 lg-mt-80">
                        <div class="agent-info bg-white border-20 p-30 mb-40">
                            <img src="{{ $property?->user?->getProfilePicture() ?? '/images/defaultUser.png' }}" data-src="{{ $property?->user?->getProfilePicture() ?? '/images/defaultUser.png' }}" alt="" class="lazy-img rounded-circle ms-auto me-auto mt-3 avatar">
                            <div class="text-center mt-25">
                                <h6 class="name">{{ $property?->user?->name ?? 'Otthonplusz' }}</h6>
                                <p class="fs-16">{{ $property?->user?->position ?? 'Referens' }}</p>
                                <ul class="style-none d-flex align-items-center justify-content-center social-icon">
                                    <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                                    <li><a href="#"><i class="fa-brands fa-linkedin"></i></a></li>
                                </ul>
                            </div>
                            <div class="divider-line mt-40 mb-45 pt-20">
                                <ul class="style-none">
                                    <li>Email: <span><a href="mailto:akabirr770@gmail.com">{{ $property?->user?->email ?? 'info@otthonplusz.hu' }}</a></span></li>
                                    <li>Phone: <span><a href="tel:+12347687565">{{ $property?->user?->phone ?? '+36301112233' }}</a></span></li>
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

@if($property->hasCoordinates())
@push('javascript')
<script>
    function initPropertyMap() {
        const propertyLocation = [{{ $property->latitude }}, {{ $property->longitude }}];

        // Térkép inicializálása
        const map = L.map("property-map").setView(propertyLocation, 15);

        // OpenStreetMap tile layer hozzáadása
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: "© OpenStreetMap contributors",
            maxZoom: 19
        }).addTo(map);

        // Marker létrehozása
        const marker = L.marker(propertyLocation).addTo(map);

        // Popup létrehozása
        const popupContent = `
            <div style="padding: 10px; min-width: 200px;">
                <h6 style="margin: 0 0 5px 0; font-weight: bold;">{{ $property->title }}</h6>
                <p style="margin: 0; font-size: 14px; color: #666;">{{ $property->getFullAddress() }}</p>
                @if($property->price)
                    <p style="margin: 5px 0 0 0; font-size: 16px; font-weight: bold; color: #007bff;">{{ number_format($property->price, 0, ',', ' ') }} Ft</p>
                @endif
            </div>
        `;

        // Popup hozzáadása a marker-hez
        marker.bindPopup(popupContent).openPopup();
    }

    // DOM betöltés után inicializálás
    document.addEventListener("DOMContentLoaded", function() {
        initPropertyMap();
    });
</script>
@endpush
@endif
