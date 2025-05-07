<div class="hero-banner-three position-relative z-1 m-1 d-flex align-items-end" style="border-radius: 0 !important; min-height: 550px !important;">
    <div class="hero-slider-one m0">
        <div class="item m0"><div class="hero-img" style="background-image: url(images/assets/example-hero.jpg);"></div></div>
        <div class="item m0"><div class="hero-img" style="background-image: url(images/media/img_27.jpg);"></div></div>
        <div class="item m0"><div class="hero-img" style="background-image: url(images/media/img_28.jpg);"></div></div>
    </div>

    <div class="container position-relative z-2">
        <div class="row">
            <div class="col-lg-10 m-auto"></div>
        </div>
        <div class="row">
            <div class="col m-auto">
                <div class="search-wrapper-one layout-one position-relative wow fadeInUp" data-wow-delay="0.2s">
                    <div class="bg-wrapper" style="margin-bottom: 100px !important;">
                        <form action="{{ route('property.list') }}">
                            <div class="d-md-flex gx-0 align-items-center">

                                {{-- Ad Type --}}
                                <div class="col">
                                    <div class="input-box-one border-left">
                                        <select class="nice-select fw-normal" name="ad_type">
                                            <option value="sell" {{ request('ad_type') == 'sell' ? 'selected' : '' }}>Eladó</option>
                                            <option value="rent" {{ request('ad_type') == 'rent' ? 'selected' : '' }}>Kiadó</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Settlements --}}
                                <div class="col">
                                    <div class="input-box-one border-left">
                                        <select class="form-select nice-select location fw-normal" id="multiple-select-clear-field" data-placeholder="Choose anything" multiple name="settlements[]">
                                            @foreach($settlements as $settlement)
                                                <option value="{{ $settlement->id }}" {{ collect(request('settlements'))->contains($settlement->id) ? 'selected' : '' }}>
                                                    {{ $settlement->name }} {{ ($settlement->part) ? ' - ' . $settlement->part : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Property Types --}}
                                <div class="col">
                                    <div class="input-box-one border-left">
                                        <select class="nice-select location fw-normal" multiple name="property_types[]">
                                            @foreach($propertyTypes as $type)
                                                <option value="{{ $type->id }}" {{ collect(request('property_types'))->contains($type->id) ? 'selected' : '' }}>
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Rooms --}}
                                <div class="col">
                                    <div class="input-box-one border-left">
                                        <label class="room fw-normal d-flex justify-content-center" for="rooms">Szobaszám</label>
                                        <div class="d-flex justify-content-center">
                                            <input type="number" name="number_of_rooms_min" value="{{ request('number_of_rooms_min') }}" class="col-3">
                                            <span class="px-2">-</span>
                                            <input name="number_of_rooms_max" type="number" value="{{ request('number_of_rooms_max') }}" class="col-3">
                                        </div>
                                    </div>
                                </div>

                                {{-- Property Size --}}
                                <div class="col">
                                    <div class="input-box-one border-left">
                                        <label class="size fw-normal d-flex justify-content-center" for="size">Méret</label>
                                        <div class="d-flex justify-content-center">
                                            <input type="number" name="property_area_min" value="{{ request('property_area_min') }}" class="col-4">
                                            <span class="px-2">-</span>
                                            <input name="property_area_max" type="number" value="{{ request('property_area_max') }}" class="col-4">
                                        </div>
                                    </div>
                                </div>

                                {{-- Price --}}
                                <div class="col">
                                    <div class="input-box-one border-left border-lg-0">
                                        <label class="price fw-normal d-flex justify-content-center" for="price">Ár</label>
                                        <div class="d-flex justify-content-center">
                                            <input type="number" name="price_min" value="{{ request('price_min') }}" class="col-4">
                                            <span class="px-2">-</span>
                                            <input name="price_max" type="number" value="{{ request('price_max') }}" class="col-4">
                                        </div>
                                    </div>
                                </div>

                                {{-- Részletes keresés (placeholder) --}}
                                <div class="col">
                                    <div class="input-box-one border-left">
                                        <select class="nice-select searchDetails fw-normal">
                                            <option value="1">Részletes keresés</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Keresés gomb --}}
                                <div class="col">
                                    <div class="input-box-one lg-mt-10">
                                        <button type="submit" class="fw-500 w-100 tran3s search-btn-three">Keresés</button>
                                    </div>
                                </div>

                            </div> <!-- /.d-md-flex -->
                            <div class="w-100 d-flex justify-content-end mt-3 input-box-one">
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#detailedSearchCollapse" aria-expanded="false" aria-controls="detailedSearchCollapse">
                                    Részletes keresés
                                </button>
                            </div>
                        </form>
                    </div> <!-- /.bg-wrapper -->
                </div> <!-- /.search-wrapper-one -->
            </div>
        </div>
    </div>
</div>
