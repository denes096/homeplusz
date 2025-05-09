@extends('layouts/homeplus')

@section('content')

    <!--
		=====================================================
			Property Listing Six
		=====================================================
		-->
    <div class="property-listing-six bg-pink-two pt-110 md-pt-80 pb-150 xl-pb-120 mt-150 xl-mt-120">
        <div class="container container-large">
            <div class="row">
                <!--
    =====================================================
    Property Listing 2
    =====================================================
    -->
                <div class="property-listing-four z-1 overflow-hidden pt-4"
                     style="background-color: #fbfbfb !important ;">
                    <div class="container container-large">
                        <div class="position-relative z-1">
                            <div class="title-one mb-60 lg-mb-40 wow fadeInUp d-flex justify-content-between">
                                <h2 class="font-garamond col-6">Ingatlan ajánlataink</h2>
                                <div class="col-3 d-flex justify-content-between">
                                    @foreach($labels as $label)
                                        <a href="/label/change/{{$label->name}}"
                                           class="fw-bold  btn-eleven">
                                            <p>{{ $label->name }}</p>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            <!-- /.title-one -->
                            @foreach($propertiesForLabels as $label => $properties)
                                <div class="asd">{{$label}}</div>
                                <div class="listing-slider-one">
                                    @include('project._partialList')
                                </div>

                            @endforeach

                        </div>
                    </div>
                    <div class="col text-center">
                        <div class="input-box-one lg-mt-10">
                            <a href="#">
                                <button class="fw-500 tran3s btn-five">További ingatlanok</button>
                            </a>
                        </div>
                        <!-- /.input-box-one -->
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
