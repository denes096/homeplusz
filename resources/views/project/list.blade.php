@extends('layouts/homeplus')

@section('content')


    <!--
		=============================================
			Inner Banner
		==============================================
		-->
    <div class="inner-banner-two inner-banner z-1 pt-160 lg-pt-130 pb-160 xl-pb-120 md-pb-80 position-relative" style="background-image: url(/images/media/img_49.jpg);">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h3 class="mb-35 xl-mb-20 pt-15 text-white">{{ $project->name }} Projekt Lista</h3>
                </div>
                <div class="col-lg-6">
                    <p class="sub-heading text-dark">{{ $project->title }}</p>
                </div>
            </div>
        </div>
    </div>


    <div class="property-listing-four z-1 overflow-hidden pt-4" style="background-color: #fbfbfb !important ;">
        <div class="container container-large">
            <div class="position-relative z-1">
                <div class="title-one mb-60 lg-mb-40 wow fadeInUp d-flex justify-content-between">
                    <h2 class="font-garamond col-6">{{ $project->name }} Projekt Lista</h2>
                </div>
                <!-- /.title-one -->

                <div class="listing-slider-one">
                    @foreach($properties as $property)
                        @include('includes.property-list-item')
                    @endforeach
                </div>
            </div>
        </div>

        <!-- /.property-listing-six -->

@endsection
