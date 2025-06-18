<div class="item zoom">
    <div class="listing-card-one style-three border-30 mb-50 bg-light p-3">
        <div class="img-gallery ">
            <div class="position-relative border-20 overflow-hidden d-flex justify-content-center" style="height: 280px">
                <div class="tag bg-white text-dark fw-500 border-20">{{$property->ad_type == 'sell' ? 'Eladó' : 'Kiadó' }}</div>
                <a href="{{ route('property.show', ['id' => $property->id]) }}">
                    <img src="{{$property->getMainImageUrl()}}" class="w-100 border-20 object-fit" alt="...">
                </a>
                <div class="img-slider-btn">
                    {{ count($property->getImageUrls()) }} <i class="fa-regular fa-image"></i>
                    @foreach($property->getImageUrls() as $url)
                        <a href="{{ $url }}" class="d-block" data-fancybox="property-{{ $property->id }}" data-caption="{{ $property->id }}"></a>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- /.img-gallery -->
        <div class="property-info mt-3 p-2 bg-white rounded-2">
            <a href="{{ route('property.show', ['id' => $property->id]) }}" class="title fs-5 tran3s fw-bold">{{$property->title}}</a>
            <div class="address fs-8" style="margin-bottom: 0 !important;"><i class="bi bi-geo-alt pe-1"></i>{{$property->settlement?->name}} {{$property->settlementPart?->name}}</div>
            <ul class="style-none feature d-flex flex-wrap align-items-center justify-content-between">
                @foreach($property->attributes as $attribute)
                    @if($attribute->pivot->value && $attribute->show_in_list)
                        <li class="d-flex align-items-center border p-1">
                            <span class="fs-16"><strong class="fw-500 color-dark">{{$attribute->prefix}}{{$attribute->pivot->value}}{{$attribute->suffix}}</strong> {{$attribute->short_label}}</span>
                        </li>
                    @endif

                @endforeach
            </ul>
            <div class="pl-footer d-flex align-items-center justify-content-between py-0" style="margin-top: 15px !important;" >
                <strong class="price fs-5 fw-bold" style="color: #96006B;">{{$property->price}}{{$property->ad_type == 'sell' ? ' M Ft' : 'E FT'}}</strong>
                <ul class="style-none d-flex action-icons">
                    <li><a href="#"><i class="fa-light fa-heart"></i></a></li>
                    <li><a href="{{ route('property.show', ['id' => $property->id]) }}" class="btn-four inverse rounded-circle" style="width: 30px; height:30px;"><i class="bi bi-chevron-right" style="color: #fff"></i></a></li>
                </ul>
            </div>
        </div>

    </div>
    <!-- /.listing-card-one -->
</div>
