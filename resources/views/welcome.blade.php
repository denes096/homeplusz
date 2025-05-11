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
                <p class="fs-2 ms-5">Kiemelet ajánlataink</p>
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
    <div class="property-listing-four z-1 overflow-hidden pt-4 pb-5" style="background-color: #fbfbfb !important ;">
        <div class="container container-large">
            <div class="position-relative z-1">
                <div class="title-one mb-60 lg-mb-40 wow fadeInUp d-flex justify-content-between">
                    <p class="fs-2 ms-5 col-6">Ingatlan ajánlataink</p>
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
                <a href="project_04.html"><p class="fs-2 col-6 nav-item">Projektjeink</p></a>
            </div>
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    @foreach($projects as $project)
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $loop->index}}" class="{{ $loop->index == 0 ? 'active' : '' }}" aria-current="true" aria-label="Slide {{$loop->index + 1}}"></button>
                    @endforeach
                </div>
                <div class="carousel-inner">
                    @foreach($projects as $project)
                        <div class="carousel-item {{ $loop->index == 0 ? 'active': '' }}">
                            <div id="isotop-gallery-wrapper" class="grid-1column lg-pt-30">
                                <div class="grid-sizer"></div>
                                <div class="isotop-item house flat pb-150">
                                    <div class="project-block-three mt-80 lg-mt-50">
                                        <div class="row gx-xxl-5 align-items-center">

                                            <div class="col-lg-6 ms-auto">
                                                <div class="caption ps-xxl-5">
                                                    <div class="tag fw-500 text-uppercase">{{ $project->project_code }}</div>
                                                    <h3 class="nav-item"><a href="/projekt/{{ $project->id }}">{{ $project->name }}</a></h3>
                                                    <p class="fs-24 pt-45 lg-pt-30 md-pt-10 pb-50 lg-pb-30 md-pb-10">
                                                        {{  $project->title }}
                                                    </p>
                                                </div>
                                                <!-- /.caption -->
                                            </div>

                                            <div class="col-lg-6">
                                                <figure class="image-wrapper position-relative z-1 overflow-hidden">
                                                   <div class="img-slider-btn">
                                                        <a href="{{ $project->getImageUrls()[0] }}"class="d-block position-relative" data-fancybox="{{ $project->id }}" data-caption="{{ $project->id }}">
                                                            <img src="{{ $project->getImageUrls()[0] }}" alt="" class="w-100 tran5s">
                                                        </a>
                                                    </div>
                                                </figure>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.project-block-three -->
                                </div>
                            </div>
                        </div>
                    @endforeach

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
