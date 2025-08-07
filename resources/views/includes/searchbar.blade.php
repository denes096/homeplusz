{{--<div class="d-block d-md-none hero-banner-three position-relative z-0  m-1 d-flex align-items-end justify-content-center pb-5" style="border-radius: 0 !important; min-height: 550px !important;">--}}
{{--    <div class="hero-slider-one m0">--}}
{{--        @foreach(\App\Models\SliderImages::all() as $sliderImage)--}}
{{--            <div class="item m0"><div class="hero-img" style="background-image: url({{ Storage::url($sliderImage->path)  }});"></div></div>--}}
{{--        @endforeach    </div>--}}
{{--    <!-- /.hero-slider-one -->--}}
{{--    <div class=" position-relative z-1" style="width:90%">--}}
{{--        <div class="row justify-content-center">--}}
{{--            <div class="col-11 m-auto text-white fs-1 fw-bold" style="text-shadow: 2px 2px 7px rgba(128,128,128,0.83);">--}}
{{--                Találjuk meg együtt új <br> otthonát!--}}
{{--            </div>--}}
{{--            <div class="bg-white rounded-5 w-90">--}}
{{--                <form action="{{ route('property.list') }}">--}}
{{--                    <div class="d-lg-flex gx-0 align-items-center">--}}
{{--                        <div class="col">--}}
{{--                            <div class="input-box-one">--}}
{{--                                <div class="switch6 bg-light rounded-3">--}}
{{--                                    <label class="switch6-light border border-secondary rounded-3">--}}
{{--                                        <input type="hidden" name="ad_type">--}}
{{--                                        <input type="checkbox" id="ad_type" {{ request('ad_type') == 'rent' ? 'checked' : '' }}>--}}
{{--                                        <span class="border">--}}
{{--                                                            <span class="switch-button " data-value="sell" id="option1">Eladó</span>--}}
{{--                                                            <span class="switch-button" data-value="rent" id="option2">Kiadó</span>--}}
{{--                                                        </span>--}}
{{--                                        <a class="btn bg-theme rounded-3"></a>--}}
{{--                                    </label>--}}
{{--                                </div>--}}

{{--                            </div>--}}
{{--                            <!-- /.input-box-one -->--}}
{{--                        </div>--}}
{{--                        <div class="col">--}}
{{--                            <div class="input-box-one">--}}
{{--                                <div class="dropdown">--}}
{{--                                    <button type="button" class="d-flex justify-content-between w-100 align-items-center border rounded-3 px-2 py-1" data-bs-toggle="dropdown">--}}
{{--                                        <input type="text" class="border-0" style="width: 60% !important" placeholder="Hol keres?"> <i class="bi bi-arrow-down-circle" style="font-size: larger !important; color: #96006B"></i>--}}
{{--                                    </button>--}}
{{--                                    <ul class="dropdown-menu city-list overflow-auto">--}}
{{--                                        @foreach($settlements as $settlement)--}}
{{--                                            <li class="d-flex flex-nowrap">--}}
{{--                                                <input class="form-check-input" type="checkbox" name="settlements[]" value="{{ $settlement->id }}" {{ collect(request('settlements'))->contains($settlement->id) ? 'checked' : '' }} id="Checkme{{$loop->index}}" />--}}
{{--                                                <label class="form-check-label" style="margin-left: 10px !important;" for="Checkme{{$loop->index}}">{{ $settlement->name }} {{ ($settlement->part) ? ' - ' . $settlement->part : '' }}</label>--}}
{{--                                            </li>--}}
{{--                                        @endforeach--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <!-- /.input-box-one -->--}}
{{--                        </div>--}}

{{--                        <div class="col">--}}
{{--                            <div class="input-box-one">--}}
{{--                                <div class="dropdown">--}}
{{--                                    <button type="button" class="d-flex justify-content-between w-100 align-items-center border rounded-3 px-2 py-1" data-bs-toggle="dropdown">--}}
{{--                                        Mit keres? <i class="bi bi-arrow-down-circle" style="font-size: larger !important; color: #96006B"></i>--}}
{{--                                    </button>--}}
{{--                                    <ul class="dropdown-menu type-list overflow-auto">--}}
{{--                                        @foreach($propertyTypes as $type)--}}
{{--                                            <li>--}}
{{--                                                <input class="form-check-input" type="checkbox" name="property_types[]" value="{{ $type->id }}" {{ collect(request('property_types'))->contains($type->id) ? 'checked' : '' }} id="Checkme{{$loop->index}}" />--}}
{{--                                                <label class="form-check-label" for="Checkme{{$loop->index}}">{{ $type->name }}</label>--}}
{{--                                            </li>--}}
{{--                                        @endforeach--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <!-- /.input-box-one -->--}}
{{--                        </div>--}}
{{--                        <div class="col d-none d-md-block">--}}
{{--                            <div class="input-box-one">--}}
{{--                                <div class="d-flex justify-content-center align-items-center">--}}
{{--                                    <label for="roomNumber location">Szobák (nappalival)</label>--}}
{{--                                </div>--}}
{{--                                <div class="d-flex justify-content-center align-items-center">--}}
{{--                                    <input type="number" name="number_of_rooms_min" placeholder="min" value="{{ request('number_of_rooms_min') }}" class="col-4 border rounded-4 px-2 py-1">--}}
{{--                                    <span class="mx-1"> - </span>--}}
{{--                                    <input type="number" name="number_of_rooms_max" placeholder="max" value="{{ request('number_of_rooms_max') }}" class="col-4 border rounded-4 px-2 py-1">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <!-- /.input-box-one -->--}}
{{--                        </div>--}}

{{--                        <div class="col d-none d-md-block">--}}
{{--                            <div class="input-box-one">--}}
{{--                                <div class="d-flex justify-content-center align-items-center">--}}
{{--                                    <label for="roomNumber location">Alapterület (m <sup>2</sup>)</label>--}}
{{--                                </div>--}}
{{--                                <div class="d-flex justify-content-center align-items-center">--}}
{{--                                    <input type="number" name="property_area_min" placeholder="min" value="{{ request('property_area_min') }}" class="col-4 border rounded-4 px-2 py-1">--}}
{{--                                    ---}}
{{--                                    <input type="number" name="property_area_max" placeholder="max" value="{{ request('property_area_max') }}" class="col-4 border rounded-4 px-2 py-1">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <!-- /.input-box-one -->--}}
{{--                        </div>--}}
{{--                        <div class="col d-none d-md-block">--}}
{{--                            <div class="input-box-one">--}}
{{--                                <div class="d-flex justify-content-center align-items-center">--}}
{{--                                    <label for="roomNumber location">Ár(millió Ft)</label>--}}
{{--                                </div>--}}
{{--                                <div class="d-flex justify-content-center align-items-center">--}}
{{--                                    <input type="number" name="price_min" placeholder="min"  value="{{ request('price_min') }}" class="col-4 border rounded-4 px-2 py-1">--}}
{{--                                    ---}}
{{--                                    <input type="number" name="price_max" placeholder="max" value="{{ request('price_max') }}"  class="col-4 border rounded-4 px-2 py-1">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <!-- /.input-box-one -->--}}
{{--                        </div>--}}
{{--                        <div class="d-flex flex-column align-items-center search-box">--}}
{{--                            <div class="input-box-one">--}}

{{--                                <button type="submit" style="background-color: #96006B !important;" class="fw-500 tran3s rounded-3 py-1 px-2"><i class="bi bi-search" style="font-size: 1.5rem !important; color: #fff"></i></button>--}}

{{--                                <div class="mega-dropdown-sm pt-1">--}}
{{--                                    <button type="button" class="d-flex justify-content-between w-100 align-items-center details-list overflow-auto rounded-3 py-1 px-2" style="border:1px solid #96006B !important;"  data-bs-toggle="dropdown" alt="">--}}
{{--                                        <i class="bi bi-funnel" style="font-size: 1.5rem !important; color: #96006B"></i>--}}
{{--                                    </button>--}}
{{--                                    <div class="dropdown-menu dropdown-menu-lg-end details-box overflow-auto mx-md-5 p-3" style="width: 80vw !important; transform: translate3d(-120px, -51px, 0px) !important;">--}}
{{--                                        <div>--}}
{{--                                            <div class="col d-block d-md-none">--}}
{{--                                                <div class="input-box-one">--}}
{{--                                                    <div class="d-flex justify-content-center align-items-center">--}}
{{--                                                        <label for="roomNumber location">Szobák (nappalival)</label>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="d-flex justify-content-center align-items-center">--}}
{{--                                                        <input type="number" name="number_of_rooms_min" placeholder="min" value="{{ request('number_of_rooms_min') }}" class="col-4 border rounded-4 px-2 py-1">--}}
{{--                                                        <span class="mx-1"> - </span>--}}
{{--                                                        <input type="number" name="number_of_rooms_max" placeholder="max" value="{{ request('number_of_rooms_max') }}" class="col-4 border rounded-4 px-2 py-1">--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <!-- /.input-box-one -->--}}
{{--                                            </div>--}}
{{--                                            <div class="col d-block d-md-none">--}}
{{--                                                <div class="input-box-one">--}}
{{--                                                    <div class="d-flex justify-content-center align-items-center">--}}
{{--                                                        <label for="roomNumber location">Alapterület (m <sup>2</sup>)</label>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="d-flex justify-content-center align-items-center">--}}
{{--                                                        <input type="number" name="property_area_min" placeholder="min" value="{{ request('property_area_min') }}" class="col-4 border rounded-4 px-2 py-1">--}}
{{--                                                        ---}}
{{--                                                        <input type="number" name="property_area_max" placeholder="max" value="{{ request('property_area_max') }}" class="col-4 border rounded-4 px-2 py-1">--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <!-- /.input-box-one -->--}}
{{--                                            </div>--}}
{{--                                            <div class="col d-block d-md-none">--}}
{{--                                                <div class="input-box-one">--}}
{{--                                                    <div class="d-flex justify-content-center align-items-center">--}}
{{--                                                        <label for="roomNumber location">Ár(millió Ft)</label>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="d-flex justify-content-center align-items-center">--}}
{{--                                                        <input type="number" name="price_min" placeholder="min"  value="{{ request('price_min') }}" class="col-4 border rounded-4 px-2 py-1">--}}
{{--                                                        ---}}
{{--                                                        <input type="number" name="price_max" placeholder="max" value="{{ request('price_max') }}"  class="col-4 border rounded-4 px-2 py-1">--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <!-- /.input-box-one -->--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <ul class="d-flex flex-wrap list-unstyled">--}}
{{--                                            <li class="p-2">--}}

{{--                                            </li>--}}
{{--                                        </ul>--}}
{{--                                        <div class="d-flex align-items-center justify-content-center">--}}
{{--                                            <button type="submit" style="background-color: #96006B !important;" class="fw-500 tran3s rounded-3 py-1 px-2">--}}
{{--                                                <i class="bi bi-search" style="font-size: 1.5rem !important; color: #fff"></i>--}}
{{--                                            </button>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <!-- /.input-box-one -->--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="form-check d-flex justify-content-start ms-4">--}}
{{--                        <input class="form-check-input" type="checkbox" value="newly_built" name="newly_built" {{ request('newly_built') == 'newly_built' ? 'checked' : '' }} id="flexCheckDefault">--}}
{{--                        <label class="form-check-label ms-2" for="flexCheckDefault">--}}
{{--                            Újépítésű--}}
{{--                        </label>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

<div class="d-none d-md-block hero-banner-three position-relative z-1  m-1 d-flex align-items-end justify-content-center" style="border-radius: 0 !important; min-height: 550px !important;">
    <div class="hero-slider-one m0">
        <div class="item m0"><div class="hero-img" style="background-image: url(/images/assets/example-hero.jpg);"></div></div>
        <div class="item m0"><div class="hero-img" style="background-image: url(/images/media/img_27.jpg);"></div></div>
        <div class="item m0"><div class="hero-img" style="background-image: url(/images/media/img_28.jpg);"></div></div>
    </div>
    <!-- /.hero-slider-one -->
    <div class=" position-relative z-1 w-100">
        <div class="row">
            <div class="col-11 m-auto text-white fs-1 fw-bold" style="text-shadow: 2px 2px 7px rgba(128,128,128,0.83);">
                Találjuk meg együtt új <br> otthonát!
            </div>
        </div>
        <div class="row">
            <div class="col-11 m-auto">
                <div class="search-wrapper-one layout-one position-relative wow fadeInUp" data-wow-delay="0.2s">
                    <div class="bg-wrapper fixed" style="margin-bottom: 55px !important; z-index: 999;">
                        <form action="{{ route('property.list') }}">
                            <div class="d-lg-flex gx-0 align-items-center">
                                <div class="col">
                                    <div class="px-1">
                                        <div class="switch6 bg-light rounded-3">
                                            <label class="switch6-light border border-secondary rounded-3">
                                                <input type="hidden" name="ad_type">
                                                <input type="checkbox" id="ad_type" {{ request('ad_type') == 'rent' ? 'checked' : '' }}>
                                                <span>
                                                            <span class="switch-button text-white" data-value="sell">Eladó</span>
                                                            <span class="switch-button" data-value="rent">Kiadó</span>
                                                        </span>
                                                <a class="btn bg-theme rounded-3"></a>
                                            </label>
                                        </div>

                                    </div>
                                    <!-- /.input-box-one -->
                                </div>
                                <div class="col">
                                    <div class="px-1">
                                        <div class="dropdown">
                                            <button type="button" class="d-flex justify-content-between w-100 align-items-center border border-secondary rounded-3 px-2 py-1" data-bs-toggle="dropdown">
                                                <input type="text" class="border-0" style="width: 60% !important" placeholder="Hol keres?"> <i class="bi bi-arrow-down-circle" style="font-size: larger !important; color: #96006B"></i>
                                            </button>
                                            <ul class="dropdown-menu city-list overflow-auto">
                                                @foreach($settlements as $settlement)
                                                    <li class="d-flex flex-nowrap">
                                                        <input class="form-check-input" type="checkbox" name="settlements[]" value="{{ $settlement->id }}" {{ collect(request('settlements'))->contains($settlement->id) ? 'checked' : '' }} id="Checkme{{$loop->index}}" />
                                                        <label class="form-check-label" style="margin-left: 10px !important;" for="Checkme{{$loop->index}}">{{ $settlement->name }} {{ ($settlement->part) ? ' - ' . $settlement->part : '' }}</label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- /.input-box-one -->
                                </div>
                                <div class="col">
                                    <div class="px-1">
                                        <div class="dropdown">
                                            <button type="button" class="d-flex justify-content-between w-100 align-items-center border border-secondary rounded-3 px-2 py-1" data-bs-toggle="dropdown">
                                                Mit keres? <i class="bi bi-arrow-down-circle" style="font-size: larger !important; color: #96006B"></i>
                                            </button>
                                            <ul class="dropdown-menu type-list overflow-auto">
                                                @foreach($propertyTypes as $type)
                                                    <li>
                                                        <input class="form-check-input" type="checkbox" name="property_types[]" value="{{ $type->id }}" {{ collect(request('property_types'))->contains($type->id) ? 'checked' : '' }} id="Checkme{{$loop->index}}" />
                                                        <label class="form-check-label" for="Checkme{{$loop->index}}">{{ $type->name }}</label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- /.input-box-one -->
                                </div>
                                <div class="col d-none d-md-block">
                                    <div class="px-1">
                                        <div id="room-label" class="w-100 align-items-center border border-secondary rounded-3 px-2 py-1">
                                            Szobák (nappalival)
                                        </div>

                                        <div id="room-inputs" class="w-100 align-items-center border border-secondary rounded-3 px-2 py-1" style="display: none;">
                                            <div class="d-flex justify-content-center">
                                                <input type="number" name="number_of_rooms_min" placeholder="min" value="{{ request('number_of_rooms_min') }}" class="col-5 border-0">
                                                <span class="col-2 mx-1"> - </span>
                                                <input type="number" name="number_of_rooms_max" placeholder="max" value="{{ request('number_of_rooms_max') }}" class="col-5 border-0">
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.input-box-one -->
                                </div>
                                <div class="col d-none d-md-block">
                                    <div class="px-1">
                                        <div id="property_area-label" class="w-100 align-items-center border border-secondary rounded-3 px-2 py-1">
                                            Alapterület (m <sup>2</sup>)
                                        </div>

                                        <div id="property_areas" class="w-100 align-items-center border border-secondary rounded-3 px-2 py-1" style="display: none;">
                                            <div class="d-flex justify-content-center">
                                                <input type="number" name="property_area_min" placeholder="min" value="{{ request('property_area_min') }}" class="col-5 border-0">
                                                <span class="col-2 mx-1"> - </span>
                                                <input type="number" name="property_area_max" placeholder="max" value="{{ request('property_area_max') }}" class="col-5 border-0">
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.input-box-one -->
                                </div>
                                <div class="col d-none d-md-block">
                                    <div class="px-1">
                                        <div id="price-label" class="w-100 align-items-center border border-secondary rounded-3 px-2 py-1">
                                            Ár(millió Ft)
                                        </div>

                                        <div id="price-inputs" class="w-100 align-items-center border border-secondary rounded-3 px-2 py-1" style="display: none;">
                                            <div class="d-flex justify-content-center">
                                                <input type="number" name="price_min" placeholder="min" value="{{ request('price_min') }}" class="col-5 border-0">
                                                <span class="col-2 mx-1"> - </span>
                                                <input type="number" name="price_max" placeholder="max" value="{{ request('price_max') }}" class="col-5 border-0">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.input-box-one -->
                                </div>
                                <div class="col d-none d-md-block">
                                    <div class="px-1">
                                        <button type="submit" style="background-color: #96006B !important; width: 100%;" class="fw-500 text-white tran3s rounded-3 py-1 px-2">Keresés<i class="bi bi-search" style="padding-left: 0.6rem; font-size: 1.5rem !important; color: #fff"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end pe-1">
                                <div class="form-check d-flex align-items-center m-0 pe-2">
                                    <input class="form-check-input" type="checkbox" value="newly_built" name="newly_built" {{ request('newly_built') == 'newly_built' ? 'checked' : '' }} id="flexCheckDefault">
                                    <label class="form-check-label ms-2 py-2" for="flexCheckDefault">
                                        Újépítésű
                                    </label>
                                </div>
                                <div class="mega-dropdown-sm pt-1 w-90">
                                    <button type="button" class="d-flex justify-content-between w-100 align-items-center text-black details-list overflow-auto rounded-3 py-1 px-2" style="border:1px solid #96006B !important;"  data-bs-toggle="dropdown" alt="">
                                        Részletes kereső<i class="bi bi-funnel" style="padding-left: 0.6rem; font-size: 1.5rem !important; color: #96006B"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-lg-end details-box overflow-auto mx-md-5 p-3" style="width: 80vw !important;">
                                        <div>
                                            <div class="row g-1" id="dont-hide">
                                                <div class="col-md-3">
                                                    <label class="form-label" for="">Ingatlantípus</label>
                                                    <select class="form-select multiselect" multiple name="property_types[]" id="">
                                                        @foreach(\App\Models\PropertyType::all() as $type)
                                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label" for="">Település rész</label>
                                                    <select class="form-select multiselect" multiple name="settlement_parts[]" id="">
                                                        @foreach(\App\Models\SettlementPart::all() as $type)
                                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <?php
                                                $propAttrsCats = \App\Models\PropertyAttributeCategory::with('propertyAttributes')->get();
                                                foreach ($propAttrsCats as $propAttrCat) {
                                                    echo "<hr>";
                                                    echo "<div class='text-center'><strong>$propAttrCat->description</strong></div>";
                                                    /**  @var $propAttrs \App\Models\PropertyAttribute[]  */
                                                    $propAttrs = $propAttrCat->propertyAttributes;
                                                foreach ($propAttrs as $propAttr) {
                                                if ($propAttr->type == 'number') { ?>
                                                <div class="col-md-3">
                                                    <div>
                                                        <label for="">{{ $propAttr->label }}</label>
                                                    </div>
                                                    <div style="display: flex">
                                                        <div style="width: 40%">
                                                            <input  style="width: 100%;"  type="number" name="{{ $propAttr->name }}_min" id="">
                                                        </div>
                                                        &nbsp;-&nbsp;
                                                        <div style="width: 40%">
                                                            <input style="width: 100%;"  type="number" name="{{ $propAttr->name }}_max" id="">
                                                        </div>
                                                    </div>
                                                </div>
                                                    <?php
                                                } else if ($propAttr->type == 'select' || $propAttr->$type == 'select_multiple') {
                                                    ?>
                                                <div class="col-md-3">
                                                    <label class="form-label" for="">{{ $propAttr->label }}</label>
                                                    <select class="form-select multiselect" multiple name="{{$propAttr->name}}[]" id="">
                                                        @foreach(json_decode($propAttr->values, true) as $id => $name)
                                                            <option value="{{ $id }}">{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <?php } else if ($propAttr->type == 'checkbox') { ?>
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input" name="{{ $propAttr->name }}" id="{{$propAttr->id}}">
                                                        <label class="form-check-label" for="{{$propAttr->id}}">{{ $propAttr->label }}</label>
                                                    </div>
                                                </div>
                                                    <?php
                                                }
                                                }
                                                } ?>
                                                <hr>
                                            </div>
                                                                                    <div class="col d-block d-md-none">
                                                <div class="input-box-one">
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <label for="roomNumber location">Szobák (nappalival)</label>
                                                    </div>
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <input type="number" name="number_of_rooms_min" placeholder="min" value="{{ request('number_of_rooms_min') }}" class="col-4 border rounded-4 px-2 py-1">
                                                        <span class="mx-1"> - </span>
                                                        <input type="number" name="number_of_rooms_max" placeholder="max" value="{{ request('number_of_rooms_max') }}" class="col-4 border rounded-4 px-2 py-1">
                                                    </div>
                                                </div>
                                                <!-- /.input-box-one -->
                                            </div>
                                            <div class="col d-block d-md-none">
                                                <div class="input-box-one">
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <label for="roomNumber location">Alapterület (m <sup>2</sup>)</label>
                                                    </div>
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <input type="number" name="property_area_min" placeholder="min" value="{{ request('property_area_min') }}" class="col-4 border rounded-4 px-2 py-1">
                                                        -
                                                        <input type="number" name="property_area_max" placeholder="max" value="{{ request('property_area_max') }}" class="col-4 border rounded-4 px-2 py-1">
                                                    </div>
                                                </div>
                                                <!-- /.input-box-one -->
                                            </div>
                                            <div class="col d-block d-md-none">
                                                <div class="input-box-one">
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <label for="roomNumber location">Ár(millió Ft)</label>
                                                    </div>
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <input type="number" name="price_min" placeholder="min"  value="{{ request('price_min') }}" class="col-4 border rounded-4 px-2 py-1">
                                                        -
                                                        <input type="number" name="price_max" placeholder="max" value="{{ request('price_max') }}"  class="col-4 border rounded-4 px-2 py-1">
                                                    </div>
                                                </div>
                                                <!-- /.input-box-one -->
                                            </div>
                                        </div>
                                        <ul class="d-flex flex-wrap list-unstyled">
                                            <li class="p-2">

                                            </li>
                                        </ul>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <button type="submit" style="background-color: #96006B !important;" class="fw-500 text-white tran3s rounded-3 py-1 px-2">
                                                Keresés <i class="bi bi-search" style="adding-left: 0.6rem; font-size: 1.5rem !important; color: #fff"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
            <!-- /.search-wrapper-one -->
        </div>
    </div>
</div>
</div>


<style>
    .switch6 {  max-width: 17em;  margin: 0 auto; }
    .switch6-light > span, .switch-toggle > span {  color: #000000; }
    .switch-toggle span span, .switch-toggle label {  color: #2b2b2b; }

    .switch-toggle a,
    .switch6-light span span { display: none; }
    .switch6-light span span:active { color: #fff !important }

    .switch6-light { display: block; height: 30px; position: relative; overflow: visible; padding: 18px; margin-left:0px; }
    .switch6-light * { box-sizing: border-box; }
    .switch6-light a { display: block; transition: all 0.3s ease-out 0s; }

    .switch6-light label,
    .switch6-light > span { line-height: 30px; vertical-align: middle;}

    .switch6-light label {font-weight: 700; margin-bottom: px; max-width: 100%;}

    .switch6-light input:focus ~ a, .switch6-light input:focus + label { outline: 1px dotted rgb(136, 136, 136); }
    .switch6-light input { position: absolute; opacity: 0; z-index: 5; }
    .switch6-light input:checked ~ a { right: 0%; }
    .switch6-light > span { position: absolute; left: -100px; width: 100%; margin-top: -14px; padding-right: 100px; text-align: left; }
    .switch6-light > span span { position: absolute; top: 0px; left: 0px; z-index: 5; display: block; width: 50%; margin-left: 100px; text-align: center; }
    .switch6-light > span span:last-child { left: 50%; }
    .switch6-light a { position: absolute; right: 50%; top: 0px; z-index: 4; display: block; background-color:#96006B !important; width: 50%; height: 100%; padding: 0px; border: none;}
    .form-check-input:checked {background-color: #96006B; border-color: #96006B; border-radius: 50% !important;}
    .city-list, .type-list, .details-box{position: absolute !important; overflow: scroll; height: 360px;}
    .details-list{position: relative !important; overflow: scroll; max-height: 200px;}
    .search-wrapper-one .bg-wrapper{padding-top: 25px !important; padding-bottom:15px !important};
    .search-box{width: 5%;}>


    @media only screen and (max-width: 600px) {
        .search-box {
            width: 100%;
            display: block !important;
        }
        .search-box button{
            width: 100% !important;
        }
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script type="text/javascript">

    $(document).ready(function() {
        $('.switch-button').on('click', function() {
            $('.switch-button').removeClass('text-white').addClass('text-black');
            $(this).removeClass('text-black').addClass('text-white');
        });

        $('#option1').addClass('text-white').removeClass('text-black');



        // ROOMS
        $('#room-label').on('click', function() {
            $(this).hide();
            $('#room-inputs').show();
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('#room-inputs, #room-label').length) {
                const min = $('#rooms-min').val().trim();
                const max = $('#rooms-max').val().trim();

                if (min === '' && max === '') {
                    $('#room-inputs').hide();
                    $('#room-label').show();
                }
            }
        });

        // PROPERTY AREA
        $('#property_area-label').on('click', function() {
            $(this).hide();
            $('#property_areas').show();
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('#property_areas, #property_area-label').length) {
                const min = $('#property_area-min').val().trim();
                const max = $('#property_area-max').val().trim();

                if (min === '' && max === '') {
                    $('#property_areas').hide();
                    $('#property_area-label').show();
                }
            }
        });

        // PRICE
        $('#price-label').on('click', function() {
            $(this).hide();
            $('#price-inputs').show();
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('#price-inputs, #price-label').length) {
                const min = $('#price-min').val().trim();
                const max = $('#price-max').val().trim();

                if (min === '' && max === '') {
                    $('#price-inputs').hide();
                    $('#price-label').show();
                }
            }
        });
    });

</script>

