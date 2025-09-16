<div class="d-block d-lg-none hero-banner-three position-relative z-0  m-1 d-flex align-items-end justify-content-center pb-5" style="border-radius: 0 !important; min-height: 550px !important;">
   <div class="hero-slider-one m0">
       @foreach(\App\Models\SliderImages::all() as $sliderImage)
            <div class="item m0">
                <div class="hero-img" style="background-image: url({{ Storage::url($sliderImage->path)  }});"></div>
            </div>
        @endforeach    
    </div>
    <!-- /.hero-slider-one -->
    <div class=" position-relative z-1" style="width:90%">
        <div class="row justify-content-center">
            <div class="col-11 m-auto text-white fs-1 fw-bold" style="text-shadow: 2px 2px 7px rgba(128,128,128,0.83);">
                Találjuk meg együtt új <br> otthonát!
            </div>
            <div class="bg-white rounded-5 w-90 py-3">
                <form action="{{ route('property.list') }}">
                    <div class="d-lg-flex gx-0 align-items-center">
                        <div class="col">
                            <div class="input-box-one">
                                <div class="switch6 bg-light rounded-3">
                                    <label class="switch6-light border border-secondary rounded-3">
                                        <input type="hidden" name="ad_type">
                                        <input type="checkbox" id="ad_type" {{ request('ad_type') == 'rent' ? 'checked' : '' }}>
                                        <span class="">
                                                            <span class="switch-button " data-value="sell" id="option1">Eladó</span>
                                                            <span class="switch-button" data-value="rent" id="option2">Kiadó</span>
                                                        </span>
                                        <a class="btn bg-theme rounded-3"></a>
                                    </label>
                                </div>

                            </div>
                            <!-- /.input-box-one -->
                        </div>
                        <div class="col">
                            <div class="input-box-one">
                                <div class="dropdown">
                                    <button type="button" class="d-flex justify-content-between w-100 align-items-center border rounded-3 px-2 py-1" data-bs-toggle="dropdown">
                                        <input type="text" class="border-0" style="width: 60% !important" placeholder="Hol keres?"> <i class="bi bi-arrow-down-circle" style="font-size: larger !important; color: #96006B"></i>
                                    </button>
                                    <ul class="dropdown-menu city-list overflow-auto">
                                        @foreach($settlements as $settlement)
                                            <li class="d-flex flex-nowrap">
                                                <input class="form-check-input" type="checkbox" name="settlements[]" value="{{ $settlement->id }}" {{ collect(request('settlements'))->contains($settlement->id) ? 'checked' : '' }} id="settlement_{{ $settlement->id }}" />
                                                <label class="form-check-label" style="margin-left: 10px !important; cursor: pointer;" for="settlement_{{ $settlement->id }}">{{ $settlement->name }} {{ ($settlement->part) ? ' - ' . $settlement->part : '' }}</label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <!-- /.input-box-one -->
                        </div>

                        <div class="col">
                            <div class="input-box-one">
                                <div class="dropdown">
                                    <button type="button" class="d-flex justify-content-between w-100 align-items-center border rounded-3 px-2 py-1" data-bs-toggle="dropdown">
                                        Mit keres? <i class="bi bi-arrow-down-circle" style="font-size: larger !important; color: #96006B"></i>
                                    </button>
                                    <ul class="dropdown-menu type-list overflow-auto">
                                        @foreach($propertyTypes as $type)
                                            <li>
                                                <input class="form-check-input" type="checkbox" name="property_types[]" value="{{ $type->id }}" {{ collect(request('property_types'))->contains($type->id) ? 'checked' : '' }} id="property_type_{{ $type->id }}" />
                                                <label class="form-check-label" style="cursor: pointer;" for="property_type_{{ $type->id }}">{{ $type->name }}</label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <!-- /.input-box-one -->
                        </div>
                        <div class="col d-none d-md-block">
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

                        <div class="col d-none d-md-block">
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
                        <div class="col d-none d-md-block">
                            <div class="input-box-one">
                                <div class="d-flex justify-content-center align-items-center">
                                    <label for="roomNumber location" id="mobile-price-label">Ár (millió Ft)</label>
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    <input type="number" name="price_min" placeholder="min"  value="{{ request('price_min') }}" class="col-4 border rounded-4 px-2 py-1">
                                    -
                                    <input type="number" name="price_max" placeholder="max" value="{{ request('price_max') }}"  class="col-4 border rounded-4 px-2 py-1">
                                </div>
                            </div>
                            <!-- /.input-box-one -->
                        </div>
                        <div class="d-flex flex-column align-items-center search-box">
                            <div class="input-box-one w-100 d-flex justify-content-evenly">

                                <button type="submit" style="background-color: #96006B !important;" class="fw-500 tran3s rounded-3 py-1 px-2"><i class="bi bi-search" style="font-size: 1.5rem !important; color: #fff"></i></button>

                                <div class="mega-dropdown-sm pt-1">
                                    <button type="button" class="d-flex justify-content-between w-100 align-items-center details-list overflow-auto rounded-3 py-1 px-2" style="border:1px solid #96006B !important;"  data-bs-toggle="dropdown" alt="">
                                        <i class="bi bi-funnel" style="font-size: 1.5rem !important; color: #96006B"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-lg-end details-box overflow-auto mx-md-5 p-3" style="width: 100vw !important; transform: translate3d(-140px, -51px, 0px) !important;">
                                        <div>
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
                                                        <label for="roomNumber location" id="mobile-detailed-price-label">Ár (millió Ft)</label>
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
                                            <button type="submit" style="background-color: #96006B !important;" class="fw-500 tran3s rounded-3 py-1 px-2">
                                                <i class="bi bi-search" style="font-size: 1.5rem !important; color: #fff"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.input-box-one -->
                        </div>
                    </div>
                    <div class="form-check d-flex justify-content-start ms-4">
                        <input class="form-check-input" type="checkbox" value="newly_built" name="newly_built" {{ request('newly_built') == 'newly_built' ? 'checked' : '' }} id="flexCheckDefault">
                        <label class="form-check-label ms-2" for="flexCheckDefault">
                            Újépítésű
                        </label>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- DESKTOP -->

    @if (request()->is('/'))
        <div class="d-none d-md-block hero-banner-three position-relative z-1  m-1 d-flex align-items-end justify-content-center" style="border-radius: 0 !important; min-height: 550px !important;">
        <div class="hero-slider-one m0">
        @foreach(\App\Models\SliderImages::all() as $sliderImage)
            <div class="item m0">
                <div class="hero-img" style="background-image: url({{ Storage::url($sliderImage->path)  }});"></div>
            </div>
        @endforeach 
        </div>
        <div class=" position-relative z-1 w-100" style="bottom: -230px;">
            <div class="row">
                <div class="col-11 m-auto text-white fs-1 fw-bold" style="text-shadow: 2px 2px 7px rgba(128,128,128,0.83);">
                    Találjuk meg együtt új <br> otthonát!
                </div>
            </div>
    @else
        <div class="d-none d-md-block position-relative z-1 mt-3 m-1 d-flex align-items-end justify-content-center" style="border-radius: 0 !important; min-height: 200px !important;">
            <div class=" position-relative z-1 w-100">
    @endif
    <!-- /.hero-slider-one -->
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
                                                        <input class="form-check-input" type="checkbox" name="settlements[]" value="{{ $settlement->id }}" {{ collect(request('settlements'))->contains($settlement->id) ? 'checked' : '' }} id="desktop_settlement_{{ $settlement->id }}" />
                                                        <label class="form-check-label" style="margin-left: 10px !important; cursor: pointer;" for="desktop_settlement_{{ $settlement->id }}">{{ $settlement->name }} {{ ($settlement->part) ? ' - ' . $settlement->part : '' }}</label>
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
                                                        <input class="form-check-input" type="checkbox" name="property_types[]" value="{{ $type->id }}" {{ collect(request('property_types'))->contains($type->id) ? 'checked' : '' }} id="desktop_property_type_{{ $type->id }}" />
                                                        <label class="form-check-label" style="cursor: pointer;" for="desktop_property_type_{{ $type->id }}">{{ $type->name }}</label>
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
                                            Ár (millió Ft)
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
                                <div class=" pt-1 w-90">
                                    <button
                                        class="d-flex justify-content-between w-100 align-items-center text-black details-list overflow-auto rounded-3 py-1 px-2"
                                        style="border:1px solid #96006B !important;"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#detailedSearchCollapse"
                                        aria-expanded="false"
                                        aria-controls="detailedSearchCollapse"
                                    >
                                        Részletes kereső
                                        <i class="bi bi-funnel" style="padding-left: 0.6rem; font-size: 1.5rem !important; color: #96006B"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="collapse mt-2" id="detailedSearchCollapse">
                                <div class=" mx-auto" style="width: 80vw !important;">
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
                                            // Skip fields that conflict with main form fields
                                            if (in_array($propAttr->name, ['property_area', 'number_of_rooms'])) {
                                                continue;
                                            }
                                            
                                            if ($propAttr->type == 'number') { ?>
                                            <div class="col-md-3">
                                                <div>
                                                    <label for="">{{ $propAttr->label }}</label>
                                                </div>
                                                <div style="display: flex">
                                                    <div style="width: 40%">
                                                        <input  style="width: 100%;"  type="number" name="{{ $propAttr->name }}_min" value="{{ request($propAttr->name . '_min', '') }}" id="">
                                                    </div>
                                                    &nbsp;-&nbsp;
                                                    <div style="width: 40%">
                                                        <input style="width: 100%;"  type="number" name="{{ $propAttr->name }}_max" value="{{ request($propAttr->name . '_max', '') }}" id="">
                                                    </div>
                                                </div>
                                            </div>
                                                <?php
                                            } else if ($propAttr->type == 'select' || $propAttr->type == 'select_multiple') {
                                                ?>
                                            <div class="col-md-3">
                                                <label class="form-label" for="">{{ $propAttr->label }}</label>
                                                <select class="form-select multiselect" multiple name="{{$propAttr->name}}[]" id="">
                                                    @foreach(json_decode($propAttr->values, true) as $id => $name)
                                                        <option value="{{ $id }}" {{ collect(request($propAttr->name, []))->contains($id) ? 'selected' : '' }}>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <?php } else if ($propAttr->type == 'checkbox') { ?>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input" name="{{ $propAttr->name }}" id="{{$propAttr->id}}" {{ request($propAttr->name) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="{{$propAttr->id}}">{{ $propAttr->label }}</label>
                                                </div>
                                            </div>
                                                <?php
                                            }
                                            }
                                            } ?>
                                            <hr>
                                        </div>
                                        <!-- Duplicate fields removed to prevent parameter duplication -->
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
        // Initialize switch state
        const currentAdType = '{{ request("ad_type", "sell") }}';
        if (currentAdType === 'rent') {
            $('#option2').addClass('text-white').removeClass('text-black');
            $('#option1').addClass('text-black').removeClass('text-white');
            $('input[type="hidden"][name="ad_type"]').val('rent');
            $('#ad_type').prop('checked', true);
        } else {
            $('#option1').addClass('text-white').removeClass('text-black');
            $('#option2').addClass('text-black').removeClass('text-white');
            $('input[type="hidden"][name="ad_type"]').val('sell');
            $('#ad_type').prop('checked', false);
        }
        
        // Initialize price labels based on current ad_type
        updatePriceLabels(currentAdType);

        $('.switch-button').on('click', function() {
            $('.switch-button').removeClass('text-white').addClass('text-black');
            $(this).removeClass('text-black').addClass('text-white');
            
            // Update hidden input value and checkbox
            const selectedValue = $(this).data('value');
            $('input[type="hidden"][name="ad_type"]').val(selectedValue);
            $('#ad_type').prop('checked', selectedValue === 'rent');
            
            // Update price labels based on ad_type
            updatePriceLabels(selectedValue);
        });



        // Update dropdown labels to show selected values after a short delay
        // to ensure checkboxes are fully restored from server-side
        setTimeout(function() {
            updateDropdownLabels();
        }, 100);
        
        // Update dropdown labels when checkboxes change
        $('input[type="checkbox"]').on('change', function() {
            updateDropdownLabels();
        });

        // Initialize multiselect for advanced filters
        $('.multiselect').each(function() {
            const $select = $(this);
            
            // Update display text (values are already restored server-side)
            updateMultiselectDisplay($select);
            
            // Handle change events
            $select.on('change', function() {
                updateMultiselectDisplay($(this));
            });
        });

        // Handle form submission to ensure all values are included
        $('form').on('submit', function(e) {
            // Ensure all multiselect values are properly submitted
            $('.multiselect').each(function() {
                const $select = $(this);
                const selectedValues = $select.val() || [];
                
                // Remove any existing hidden inputs for this field
                $select.siblings('input[type="hidden"][name="' + $select.attr('name') + '"]').remove();
                
                // Add hidden inputs for each selected value
                selectedValues.forEach(function(value) {
                    $select.after('<input type="hidden" name="' + $select.attr('name') + '" value="' + value + '">');
                });
            });
        });

        // Initialize range inputs
        initializeRangeInputs();
        
        // Also initialize after a short delay to ensure everything is loaded
        setTimeout(function() {
            initializeRangeInputs();
        }, 200);

        // ROOMS
        $('#room-label').on('click', function() {
            $(this).hide();
            $('#room-inputs').show();
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('#room-inputs, #room-label').length) {
                const minElement = $('#room-inputs input[name="number_of_rooms_min"]');
                const maxElement = $('#room-inputs input[name="number_of_rooms_max"]');
                
                if (minElement.length && maxElement.length) {
                    const min = minElement.val().trim();
                    const max = maxElement.val().trim();

                    if (min === '' && max === '') {
                        $('#room-inputs').hide();
                        $('#room-label').show().text('Szobák (nappalival)');
                    } else {
                        $('#room-inputs').hide();
                        $('#room-label').show().text((min || 'min') + ' - ' + (max || 'max'));
                    }
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
                const minElement = $('#property_areas input[name="property_area_min"]');
                const maxElement = $('#property_areas input[name="property_area_max"]');
                
                if (minElement.length && maxElement.length) {
                    const min = minElement.val().trim();
                    const max = maxElement.val().trim();

                    if (min === '' && max === '') {
                        $('#property_areas').hide();
                        $('#property_area-label').show().text('Alapterület (m²)');
                    } else {
                        $('#property_areas').hide();
                        $('#property_area-label').show().text((min || 'min') + ' - ' + (max || 'max') + ' m²');
                    }
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
                const minElement = $('#price-inputs input[name="price_min"]');
                const maxElement = $('#price-inputs input[name="price_max"]');
                
                if (minElement.length && maxElement.length) {
                    const min = minElement.val().trim();
                    const max = maxElement.val().trim();
                    const adType = $('input[type="hidden"][name="ad_type"]').val();

                    if (min === '' && max === '') {
                        $('#price-inputs').hide();
                        $('#price-label').show().text(adType === 'rent' ? 'Ár (ezer Ft)' : 'Ár (millió Ft)');
                    } else {
                        $('#price-inputs').hide();
                        const unit = adType === 'rent' ? 'E Ft' : 'M Ft';
                        $('#price-label').show().text((min || 'min') + ' - ' + (max || 'max') + ' ' + unit);
                    }
                }
            }
        });
    });

    // Function to update price labels based on ad_type
    function updatePriceLabels(adType) {
        const isRent = adType === 'rent';
        const priceLabelText = isRent ? 'Ár (ezer Ft)' : 'Ár (millió Ft)';
        
        // Update desktop price label
        $('#price-label').text(priceLabelText);
        
        // Update mobile price labels
        $('#mobile-price-label').text(priceLabelText);
        $('#mobile-detailed-price-label').text(priceLabelText);
    }

    // Function to update dropdown labels with selected values
    function updateDropdownLabels() {
        // Determine which searchbar version is visible
        const isMobile = $('.d-block.d-lg-none').is(':visible');
        
        // Update settlements dropdown - select from the appropriate version
        const selectedSettlements = [];
        if (isMobile) {
            $('.d-block.d-lg-none input[name="settlements[]"]:checked').each(function() {
                selectedSettlements.push($(this).next('label').text().trim());
            });
        } else {
            $('.d-none.d-md-block input[name="settlements[]"]:checked').each(function() {
                selectedSettlements.push($(this).next('label').text().trim());
            });
        }
        
        // Clear all settlements inputs first
        $('input[placeholder="Hol keres?"]').val('');
        
        // Update only the visible settlements input
        const settlementsInput = $('input[placeholder="Hol keres?"]:visible').first();
        if (selectedSettlements.length > 0) {
            console.log(selectedSettlements);
            settlementsInput.val(selectedSettlements.join(', '));
        }

        // Update property types dropdown - select from the appropriate version
        const selectedTypes = [];
        if (isMobile) {
            $('.d-block.d-lg-none input[name="property_types[]"]:checked').each(function() {
                selectedTypes.push($(this).next('label').text().trim());
            });
        } else {
            $('.d-none.d-md-block input[name="property_types[]"]:checked').each(function() {
                selectedTypes.push($(this).next('label').text().trim());
            });
        }
        
        // Reset all property type buttons first
        $('button:contains("Mit keres?")').html('Mit keres? <i class="bi bi-arrow-down-circle" style="font-size: larger !important; color: #96006B"></i>');
        
        // Update only the visible property types button
        const typesButton = $('button:contains("Mit keres?"):visible').first();
        if (selectedTypes.length > 0) {
            typesButton.html(selectedTypes.join(', ') + ' <i class="bi bi-arrow-down-circle" style="font-size: larger !important; color: #96006B"></i>');
        }
    }


    // Function to initialize range inputs
    function initializeRangeInputs() {
        // Rooms
        const roomsMin = '{{ request("number_of_rooms_min", "") }}';
        const roomsMax = '{{ request("number_of_rooms_max", "") }}';
        console.log('Initializing rooms:', roomsMin, roomsMax);
        console.log('All request data:', @json(request()->all()));
        
        if (roomsMin || roomsMax) {
            $('#room-label').hide();
            $('#room-inputs').show();
            $('#room-inputs input[name="number_of_rooms_min"]').val(roomsMin);
            $('#room-inputs input[name="number_of_rooms_max"]').val(roomsMax);
            
            // Update the label to show the selected values
            const roomLabelText = (roomsMin || 'min') + ' - ' + (roomsMax || 'max');
            $('#room-label').text(roomLabelText);
        }

        // Property Area
        const areaMin = '{{ request("property_area_min", "") }}';
        const areaMax = '{{ request("property_area_max", "") }}';
        console.log('Initializing area:', areaMin, areaMax);
        if (areaMin || areaMax) {
            $('#property_area-label').hide();
            $('#property_areas').show();
            $('#property_areas input[name="property_area_min"]').val(areaMin);
            $('#property_areas input[name="property_area_max"]').val(areaMax);
            
            // Update the label to show the selected values
            const areaLabelText = (areaMin || 'min') + ' - ' + (areaMax || 'max') + ' m²';
            $('#property_area-label').text(areaLabelText);
        }

        // Price
        const priceMin = '{{ request("price_min", "") }}';
        const priceMax = '{{ request("price_max", "") }}';
        const adType = '{{ request("ad_type", "sell") }}';
        console.log('Initializing price:', priceMin, priceMax);
        if (priceMin || priceMax) {
            $('#price-label').hide();
            $('#price-inputs').show();
            $('#price-inputs input[name="price_min"]').val(priceMin);
            $('#price-inputs input[name="price_max"]').val(priceMax);
            
            // Update the label to show the selected values with correct unit
            const unit = adType === 'rent' ? 'E Ft' : 'M Ft';
            const priceLabelText = (priceMin || 'min') + ' - ' + (priceMax || 'max') + ' ' + unit;
            $('#price-label').text(priceLabelText);
        }
    }

    // Function to update multiselect display
    function updateMultiselectDisplay($select) {
        const selectedValues = $select.val() || [];
        const selectedTexts = [];
        
        $select.find('option:selected').each(function() {
            selectedTexts.push($(this).text());
        });
        
        if (selectedTexts.length > 0) {
            $select.attr('title', selectedTexts.join(', '));
            $select.css('color', '#000');
        } else {
            $select.attr('title', 'Válasszon...');
            $select.css('color', '#6c757d');
        }
    }

</script>

