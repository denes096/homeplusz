@extends('layouts/homeplus')

@section('content')

    <!--
=====================================================
    Property Listing
=====================================================
-->

    <div class="property-listing-four z-1 overflow-hidden pt-4">
        <div class="container container-large">
            <div class="position-relative z-1">
                <div class="title-one mb-60 lg-mb-40 wow fadeInUp">
                <h2 class="font-garamond">Kiemelet ajánlataink</h2>
                </div>
                <!-- /.title-one -->

                <div class="listing-slider-one">
                    @foreach($featuredProperties as $property)
                        @include('includes/property-list-item')
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!--
=====================================================
    Property Listing 2
=====================================================
-->
    <div class="property-listing-four z-1 overflow-hidden pt-4" style="background-color: #fbfbfb !important ;">
        <div class="container container-large">
            <div class="position-relative z-1">
                <div class="title-one mb-60 lg-mb-40 wow fadeInUp d-flex justify-content-between">
                    <h2 class="font-garamond col-6">Ingatlan ajánlataink</h2>
                    <div class="col-3 d-flex justify-content-between">
                        @foreach($labels as $index => $label)
                            <a href="#"
                               class="fw-bold btn-eleven label-button {{ $index === 0 ? 'active' : '' }}"
                               data-label="{{ $label->name }}">
                                <p>{{ $label->name }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
                <!-- /.title-one -->

                @foreach($labels as $index => $label)
                    <div class="property-slider-container" data-label="{{ $label->name }}">
                        <h3>{{ $label->name }}</h3>
                        <div class="listing-slider-one">
                            @foreach($propertiesForLabels[$label->name] as $property)
                                @include('includes/property-list-item')
                            @endforeach
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
        <div class="col text-center">
            <div class="input-box-one lg-mt-10">
                <a href="#"><button class="fw-500 tran3s btn-five" >További ingatlanok</button></a>
            </div>
            <!-- /.input-box-one -->
        </div>
    </div>

    <!--
		=====================================================
			Property Listing Four Projects
		=====================================================
		-->
    <div class="project-section-one pt-40">
        <div class="container mb-50">
            <div class="title-one mb-60 lg-mb-40 wow fadeInUp">
                <a href="project_04.html"><h2 class="font-garamond col-6 nav-item">Projektjeink</h2></a>
            </div>
            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div id="isotop-gallery-wrapper" class="grid-1column lg-pt-30">
                            <div class="grid-sizer"></div>
                            <div class="isotop-item house flat pb-150">
                                <div class="project-block-three mt-80 lg-mt-50">
                                    <div class="row gx-xxl-5 align-items-center">

                                        <div class="col-lg-6 ms-auto">
                                            <div class="caption ps-xxl-5">
                                                <div class="tag fw-500 text-uppercase">X PROJECT II</div>
                                                <h3 class="nav-item"><a href="project_list.html">Lorem Ipsum</a></h3>
                                                <p class="fs-24 pt-45 lg-pt-30 md-pt-10 pb-50 lg-pb-30 md-pb-10">xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx <br> xxxxxxxxxxxxxxxxxxxxxxxxxx </p>
                                            </div>
                                            <!-- /.caption -->
                                        </div>

                                        <div class="col-lg-6">
                                            <figure class="image-wrapper position-relative z-1 overflow-hidden">
                                                <a href="images/project/img_22.jpg" class="d-block position-relative" data-fancybox data-caption="Apartments on Vintage City">
                                                    <img src="images/project/img_22.jpg" alt="" class="w-100 tran5s">
                                                </a>
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.project-block-three -->
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div id="isotop-gallery-wrapper" class="grid-1column lg-pt-30">
                            <div class="grid-sizer"></div>
                            <div class="isotop-item house flat pb-150">
                                <div class="project-block-three mt-80 lg-mt-50">
                                    <div class="row gx-xxl-5 align-items-center">

                                        <div class="col-lg-6 ms-auto">
                                            <div class="caption ps-xxl-5">
                                                <div class="tag fw-500 text-uppercase">X PROJECT I</div>
                                                <h3 class="nav-item"><a href="project_list.html">Lorem OAKFKAMf</a></h3>
                                                <p class="fs-24 pt-45 lg-pt-30 md-pt-10 pb-50 lg-pb-30 md-pb-10">xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx <br> xxxxxxxxxxxxxxxxxxxxxxxxxx </p>
                                            </div>
                                            <!-- /.caption -->
                                        </div>

                                        <div class="col-lg-6">
                                            <figure class="image-wrapper position-relative z-1 overflow-hidden">
                                                <a href="images/project/img_22.jpg" class="d-block position-relative" data-fancybox data-caption="Apartments on Vintage City">
                                                    <img src="images/project/img_22.jpg" alt="" class="w-100 tran5s">
                                                </a>
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.project-block-three -->
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <hr>
    </div>


    <div>
        <hr>
    </div>
    @include('includes/areas')

    <!--
=============================================
    NUMBERS
==============================================
-->
@endsection
