<div class="item zoom">
    <div class="listing-card-one style-three border-30 mb-3 bg-light p-3">
        <div class="img-gallery ">
            <div class="position-relative border-20 overflow-hidden d-flex justify-content-center" style="height: 280px">

                <div class="tag bg-transparent d-flex">
                    <!-- Max 4 tag in display -->
                    <div class="bg-white text-dark fw-bold rounded-3 w-100 mx-1 px-3">
                        <div>
                            {{$property->ad_type }}
                        </div>
                    </div>
                    <div class="text-dark fw-bold rounded-3 w-100 mx-1 px-3">
                        <div>
                            egyéb
                        </div>
                    </div>
                </div>

                <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <a href="{{ route('property.show', ['id' => $property->id]) }}" >
                                <img src="{{$property->getMainImageUrl()}}" class="w-85 border-20 d-flex justify-content-center align-items-center" alt="..."> <!-- automatikus alt -->
                            </a>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
                        <span class="visually-hidden p-2">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>


                <div class="img-slider-btn">
                    {{ count($property->getImageUrls()) }} <i class="fa-regular fa-image"></i>
                    @foreach($property->getImageUrls() as $url)
                        <a href="{{ $url }}" class="d-block" data-fancybox="{{ $property->id }}" data-caption="{{ $property->id }}"></a>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- /.img-gallery -->
        <div class="property-info mt-3 p-2 bg-white rounded-2">
            <a href="{{ route('property.show', ['id' => $property->id]) }}" class="title tran3s fw-bold" style="font-size: 16px !important; min-height: 52px; max-height: 52px;" >{{$property->title}}</a>
            <div class="address" style="margin-bottom: 0 !important; font-size: 14px !important;"><i class="bi bi-geo-alt pe-1"></i>{{$property->settlement?->name}} {{$property->settlementPart?->name}}</div>
            <ul class="style-none feature d-flex flex-wrap align-items-center justify-content-evenly" style="min-height: 55px; max-height: 55px;">
                @foreach($property->attributes as $attribute)
                    @if($attribute->pivot->value)
                        <li class="d-flex align-items-center border p-1">
                            <span><strong class="fw-500 color-dark" style="font-size: 14px !important;">{{$attribute->prefix}}{{$attribute->pivot->value}}{{$attribute->suffix}}</strong> {{$attribute->short_label}}</span>
                        </li>
                        @else($attribute->pivot->value)
                            <div class="d-block"  style="min-height: 55px; max-height: 55px;">
                                <span><strong class="fw-500 color-dark">----------</strong></span>
                            </div>
                    @endif

                @endforeach
            </ul>
            <div class="pl-footer d-flex align-items-center justify-content-between py-0" style="margin-top: 15px !important;" >
                <strong class="price fw-bold" style="color: #96006B; font-size: 16px !important;">{{$property->price}}{{$property->ad_type == 'sell' ? ' M Ft' : 'E FT'}}</strong>
                <ul class="style-none d-flex action-icons">
                    <li><a href="#"><i class="fa-light fa-heart"></i></a></li>
                    <li><a href="{{ route('property.show', ['id' => $property->id]) }}" class="btn-four inverse rounded-circle" style="width: 30px; height:30px;"><i class="bi bi-chevron-right" style="color: #fff"></i></a></li>
                </ul>
            </div>
        </div>

    </div>
    <!-- /.listing-card-one -->
</div>
