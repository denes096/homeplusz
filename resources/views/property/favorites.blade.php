@extends('layouts/homeplus')

@section('content')

    <!--
		=====================================================
			Property Favorites
		=====================================================
		-->
    <div class="property-listing-six bg-pink-two pt-110 md-pt-80 pb-150 xl-pb-120 mt-150 xl-mt-120">
        <div class="container container-large">
            <div class="row">
                <!--
    =====================================================
    Property Favorites
    =====================================================
    -->
                <div class="property-listing-four z-1 overflow-hidden pt-4"
                     style="background-color: #fbfbfb !important ;">
                    <div class="container container-large">
                        <div class="position-relative z-1">
                            <div class="title-one mb-60 lg-mb-40 wow fadeInUp d-flex justify-content-between">
                                <h2 class="font-garamond col-6">Kedvenceim</h2>
                            </div>

                            <div class="row gx-xxl-5" id="favorites-container">
                                @if($properties->count() > 0)
                                    @foreach($properties as $property)
                                        <div class="col-lg-4 col-md-6">
                                            @include('includes/property-list-item')
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <div class="text-center py-5">
                                            <i class="fa-light fa-heart" style="font-size: 4rem; color: #96006B; margin-bottom: 1rem;"></i>
                                            <h4 class="mb-3">Még nincsenek kedvenceid</h4>
                                            <p class="text-muted mb-4">Kattints a szív ikonra az ingatlanok mellett, hogy hozzáadd őket a kedvenceidhez!</p>
                                            <a href="{{ route('property.list') }}" class="btn-ten">Ingatlanok böngészése</a>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection


