<div class="property-listing-four z-1 overflow-hidden pt-4">
    <div class="container container-large">
        <div class="position-relative z-1">
            <div class="title-one mb-60 lg-mb-40 wow fadeInUp">
                <h2 class="font-garamond">{{$settlementGroup->settlement->name}}</h2>
            </div>
            <div class="font-garamond">
            @foreach($settlementGroup->settlements as $settlement)
                {{$settlement->name}}
            @endforeach
            </div>
            <!-- /.title-one -->

            <div class="listing-slider-one">
                @foreach($properties as $property)
                    @include('includes/property-list-item')
                @endforeach
            </div>
        </div>
    </div>
</div>
