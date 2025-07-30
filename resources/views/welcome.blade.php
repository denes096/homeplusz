@extends('layouts/homeplus')

@section('content')

    <!--
=====================================================
    Property Listing
=====================================================
-->

    <div class="container container-large property-listing-four  overflow-hidden pt-2">
        <div class="">
            <div class="position-relative">
                <div class="title-one lg-mb-40 wow fadeInUp">
                <p class="fs-4 ms-md-5  mt-0">Kiemelet ajánlataink</p>
                </div>
                <!-- /.title-one -->

                <div id="" class=" slide carousel-dark" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="slider"></button>
                        <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="listing-slider-one mb-5">
                                @foreach($featuredProperties as $property)
                                    @include('includes/property-list-item')
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselIndicators" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselIndicators" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!--
=====================================================
    Property Listing 2
=====================================================
-->
    <div class="property-listing-four overflow-hidden py-2" style="background-color: #fbfbfb !important ;">
        <div class="container container-large">
            <div class="position-relative">
                <div class="title-one wow fadeInUp d-md-flex align-items-center justify-content-between">
                    <p class="fs-4 ms-md-5 col-6 mt-0">Ingatlan ajánlataink</p>
                    <div class=" d-flex justify-content-between text-decoration-none me-3">
                        @foreach($labels as $index => $label)
                            <a href="#"
                               class="me-3 d-flex align-items-center justify-conent-center border rounded-3 px-3 fw-bold btn-tag label-button {{ $index === 0 ? 'active' : '' }}"
                               data-label="{{ $label->name }}" style="font-size: 14px !important;">
                                <p class="m-0">{{ $label->name }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
                <!-- /.title-one -->

                <div id="carouselIndicators2" class="carousel slide carousel-dark" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselIndicators2" data-bs-slide-to="0" class="active" aria-current="true" aria-label="slider"></button>
                        <button type="button" data-bs-target="#carouselIndicators2" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselIndicators2" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            @foreach($labels as $index => $label)
                                <div class="property-slider-container" data-label="{{ $label->name }}">
                                    
                                    <div class="listing-slider-one">
                                        @foreach($propertiesForLabels[$label->name] as $property)
                                            @include('includes/property-list-item')
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselIndicators2" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselIndicators2" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
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
    <div class="project-section-one pt-2">
        <div class="container mb-50">
            <div class="title-one wow fadeInUp">
                <a href="project_04.html"><p class="fs-4 fw-bold col-6 nav-item">Projektjeink</p></a>
            </div>
            <div id="carouselIndicators3" class="carousel slide carousel-dark" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselIndicators3" data-bs-slide-to="0" class="active" aria-current="true" aria-label="slider"></button>
                        <button type="button" data-bs-target="#carouselIndicators3" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselIndicators3" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>
                <div class="carousel-inner">
                <div class="carousel-item active">
                    @foreach($projects as $project)
                        <div class="carousel-item {{ $loop->index == 0 ? 'active': '' }}">
                            <div id="isotop-gallery-wrapper" class="grid-1column lg-pt-30">
                                <div class="grid-sizer"></div>
                                <div class="isotop-item house flat">
                                    <div class="project-block-three ">
                                        <div class="row gx-xxl-5 align-items-center">

                                            <div class="col-lg-4 ms-auto">
                                                <div class="caption ps-xxl-5">
                                                    <div class="tag fw-500 text-uppercase">{{ $project->project_code }}</div>
                                                    <h4 class="nav-item"><a href="/projekt/{{ $project->id }}">{{ $project->name }}</a></h4>
                                                    <p class="fs-24 ">
                                                        {{  $project->title }}
                                                    </p>
                                                    <div class="d-flex justify-content-center">
                                                        <a href="/projekt/{{ $project->id }}" class="btn-ten my-5" style="line-height: 40px;"><span>A projekt lakásai</span><i class="bi bi-chevron-right" style="color: #fff"></i></a>
                                                    </div>
                                                </div>
                                                <!-- /.caption -->
                                            </div>

                                            <div class="col-lg-5">
                                                <figure class="position-relative overflow-hidden circle-1">
                                                   <div class="img-slider-btn">
                                                        <a href="{{ $project->getImageUrls()[0] }}"class="d-block position-relative" data-fancybox="{{ $project->id }}" data-caption="{{ $project->id }}">
                                                            <img src="{{ $project->getImageUrls()[0] }}" alt="" class="zoom tran5s img-fluid rounded-5">
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
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselIndicators3" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselIndicators3" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
        <hr>
    </div>
    <div>
    </div>
    @include('includes/areas')

    <!--
=============================================
    NUMBERS
==============================================
-->
@endsection

<style>
.circle-1 {


  mix-blend-mode: multiply;
}
.circle-1 {
  background: transparent;
  border-radius: 20% 31% 38% 54%/26% 47% 79% 52%;
}

.btn-tag.active{
    border: 1px solid #96006B;
    border-radius: 0.5rem;
    background-color: #96006B;
    color: #fff !important;
    text-decoration: none !important;
    padding: 0.2rem 1.5rem 0.2rem 1.5rem;
}
</style>