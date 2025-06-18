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
                            </div>

                            <div class="row gx-xxl-5">
                                    <!-- /.title-one -->
                                    @foreach($properties as $property)
                                    <div class="col-lg-4 col-md-6">
                                        @include('includes/property-list-item')
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                    <div class="col text-center">
                                {{ $properties->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
                        <!-- /.input-box-one -->
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
