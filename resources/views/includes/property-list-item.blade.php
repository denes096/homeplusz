<div class="item zoom">
    <div class="listing-card-one style-three border-30 mb-50">
        <div class="img-gallery p-15">
            <div class="position-relative border-20 overflow-hidden" style="height: 320px">
                <div class="tag bg-white text-dark fw-500 border-20">{{$property->ad_type }}</div>
                <img src="{{$property->getMainImageUrl()}}" class="w-100 border-20 object-fit-cover" alt="">
                <a href="{{ route('property.show', ['id' => $property->id]) }}" class="btn-four inverse rounded-circle position-absolute"><i class="bi bi-arrow-up-right"></i></a>
                <div class="img-slider-btn">
                    {{ count($property->getImageUrls()) }} <i class="fa-regular fa-image"></i>
                    @foreach($property->getImageUrls() as $url)
                        <a href="{{ $url }}" class="d-block" data-fancybox="{{ $property->id }}" data-caption="{{ $property->id }}"></a>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- /.img-gallery -->
        <div class="property-info pe-4 ps-4">
            <a href="{{ route('property.show', ['id' => $property->id]) }}" class="title tran3s">{{$property->title}}</a>
            <div class="address" style="margin-bottom: 0 !important;">{{$property->settlement?->name}} {{$property->settlementPart?->name}}</div>
            <ul class="style-none feature d-flex flex-wrap align-items-center justify-content-between">
                @foreach($property->attributes as $attribute)
                    @if($attribute->pivot->value)
                        <li class="d-flex align-items-center">
                            <!--itt is kellenek az ingatlan periféria svg-k!  -->
                            <span class="fs-16"><img src="" alt=""><strong class="fw-500 color-dark">{{$attribute->prefix}}{{$attribute->pivot->value}}{{$attribute->suffix}}</strong> {{$attribute->short_label}}</span>
                        </li>
                    @endif

                @endforeach
            </ul>
            <div class="pl-footer top-border d-flex align-items-center justify-content-between" style="margin-top: 20px !important;" >
                <strong class="price fw-500 color-dark">{{$property->price}}{{$property->ad_type == 'sell' ? 'M Ft' : 'E Ft'}}</strong>
                <ul class="style-none d-flex action-icons">
                    <li><a href="#"><i class="fa-light fa-heart"></i></a></li>
                    <li><a href="{{ route('property.show', ['id' => $property->id]) }}" class="btn-four hover-dark inverse rounded-circle" style="width: 30px; height:30px;"><i class="bi bi-arrow-up-right"></i></a></li>
                </ul>
            </div>
        </div>

    </div>
    <!-- /.listing-card-one -->
</div>
