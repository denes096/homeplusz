@extends('layouts/homeplus')

@section('searchbar')@endsection

@section('content')
    <!--
		=====================================================
			Agent Details
		=====================================================
		-->
    <div class="agent-details theme-details-one mt-130 xl-mt-100 pb-150 xl-pb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="info-pack-one mb-80 xl-mb-50">
                        <div class="row">
                            <div class="col-xl-6 d-flex">
                                <div class="media position-relative z-1 w-100 me-xl-4" style="background-image: url({{ $user->getProfilePicture() }});">
                                    <div class="tag bg-white position-absolute text-uppercase">{{ $user->position }}</div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="ps-xxl-3 pe-xxl-3 pe-xl-0 ps-xl-0 pe-3 ps-3 pt-40 lg-pt-30 pb-45 lg-pb-30">
                                    <h4>{{ $user->name }}</h4>
                                    <!--
                                    <div class="designation fs-16">Marketing manager</div>
                                    -->
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                            <!--
                                            <tr>
                                                <td>Terület: </td>
                                                <td>Érd, Tárnok, Százhalombatta </td>
                                            </tr>
                                            -->
                                            <tr>
                                                <td>Telefon: </td>
                                                <td>+36030 555 66 55</td>
                                            </tr>
                                            <tr>
                                                <td>Email:</td>
                                                <td>dori@otthonplusz.hu</td>
                                            </tr>
                                            <!--
                                            <tr>
                                                <td>Egyéb:</td>
                                                <td>xxxxx</td>
                                            </tr>
                                            -->
                                            </tbody>
                                        </table>
                                    </div>
                                    <ul class="style-none d-flex align-items-center social-icon">
                                        <li><a href="#"><i class="fa-brands fa-whatsapp"></i></a></li>
                                        <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                                        <li><a href="#"><i class="fa-brands fa-viber"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.info-pack-one -->
                    <div class="agent-overview bottom-line-dark pb-40 mb-80 xl-mb-50">
                        <h4 class="mb-20">Áttekintés</h4>
                        <p class="fs-20 lh-lg pb-15">{{ $user->description ?? 'Leírás...' }}</p>
                    </div>
                    <!-- /.agent-overview -->


                    <!--
                    <div class="agent-property-listing bottom-line-dark pb-20 mb-80 xl-mb-50">
                        <div class="d-sm-flex justify-content-between align-items-center mb-40 xs-mb-20">
                            <h4 class="mb-10">Referens ingatlanjai</h4>
                            <div class="filter-nav-one xs-mt-40">
                                <ul class="style-none d-flex justify-content-center flex-wrap isotop-menu-wrapper">
                                    <li class="is-checked" data-filter="*">Összes</li>
                                    <li data-filter=".sell">Eladó</li>
                                    <li data-filter=".rent">Kiadó</li>
                                </ul>
                            </div>
                        </div>
                        <div id="isotop-gallery-wrapper" class="grid-2column">
                            <div class="grid-sizer"></div>
                            <div class="isotop-item rent">
                                <div class="listing-card-one shadow-none style-two mb-50 rounded-3 zoom">
                                    <div class="img-gallery">
                                        <div class="position-relative overflow-hidden">
                                            <div class="tag bg-white text-dark fw-500">Kiadó</div>
                                            <img src="images/listing/img_69.jpg" class="w-100" alt="...">

                                            <div class="img-slider-btn">
                                                03 <i class="fa-regular fa-image"></i>
                                                <a href="images/listing/img_large_01.jpg" class="d-block" data-fancybox="img1" data-caption="Érd"></a>
                                                <a href="images/listing/img_large_02.jpg" class="d-block" data-fancybox="img1" data-caption="Érd"></a>
                                                <a href="images/listing/img_large_03.jpg" class="d-block" data-fancybox="img1" data-caption="Érd"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="property-info">
                                        <a href="listing_details_01.html" class="title tran3s">Kiadó Családi ház</a>
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
                                            <ul class="style-none d-flex action-icons pe-3">
                                                <li><a href="#"><i class="fa-light fa-heart"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                 /.listing-card-one -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
