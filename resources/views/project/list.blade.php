@extends('layouts/homeplus')

@section('content')


    <!--
		=============================================
			Inner Banner
		==============================================
		-->
    <div class="inner-banner-two inner-banner z-1 pt-160 lg-pt-130 pb-160 xl-pb-120 md-pb-80 position-relative" style="background-image: url(images/media/img_49.jpg);">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h3 class="mb-35 xl-mb-20 pt-15 text-white">{{ $project->name }} Lista</h3>
                </div>
                <div class="col-lg-6">
                    <p class="sub-heading text-dark">Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis hic at labore ullam?</p>
                </div>
            </div>
        </div>
    </div>


    <div class="property-listing-four z-1 overflow-hidden pt-4" style="background-color: #fbfbfb !important ;">
        <div class="container container-large">
            <div class="position-relative z-1">
                <div class="title-one mb-60 lg-mb-40 wow fadeInUp d-flex justify-content-between">
                    <h2 class="font-garamond col-6">X Projekt Lista</h2>
                </div>
                <!-- /.title-one -->

                <div class="listing-slider-one">
                    <div class="item zoom">
                        <div class="listing-card-one style-three border-30 mb-50">
                            <div class="img-gallery p-15">
                                <div class="position-relative border-20 overflow-hidden">
                                    <div class="tag bg-white text-dark fw-500 border-20">Folyamat</div>
                                    <img src="images/listing/img_13.jpg" class="w-100 border-20" alt="...">
                                    <a href="project_details_01.html" class="btn-four inverse rounded-circle position-absolute"><i class="bi bi-arrow-up-right"></i></a>
                                    <div class="img-slider-btn">
                                        03 <i class="fa-regular fa-image"></i>
                                        <a href="images/listing/img_large_01.jpg" class="d-block" data-fancybox="img1" data-caption="Érd"></a>
                                        <a href="images/listing/img_large_02.jpg" class="d-block" data-fancybox="img1" data-caption="Érd"></a>
                                        <a href="images/listing/img_large_03.jpg" class="d-block" data-fancybox="img1" data-caption="Érd"></a>
                                    </div>
                                </div>
                            </div>
                            <!-- /.img-gallery -->
                            <div class="property-info pe-4 ps-4">
                                <a href="listing_details_01.html" class="title tran3s">X Project Lakása</a>
                                <div class="address">Érd</div>
                                <ul class="style-none feature d-flex flex-wrap align-items-center justify-content-between">
                                    <li class="d-flex align-items-center">
                                        <span class="fs-16"><strong class="fw-500 color-dark">100</strong>㎡</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-16"><strong class="fw-500 color-dark">10</strong> szoba</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-16"><strong class="fw-500 color-dark">02</strong> fűrdőszoba</span>
                                    </li>
                                </ul>
                                <div class="pl-footer top-border d-flex align-items-center justify-content-between">
                                    <strong class="price fw-500 color-dark">70M Ft.</strong>
                                    <ul class="style-none d-flex action-icons">
                                        <li><a href="#"><i class="fa-light fa-heart"></i></a></li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                        <!-- /.listing-card-one -->
                    </div>
                </div>
            </div>
        </div>

        <!-- /.property-listing-six -->

@endsection
