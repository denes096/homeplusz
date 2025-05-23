<!-- <div class="hero-banner-three position-relative z-1 m-1 d-flex align-items-end" style="border-radius: 0 !important; min-height: 550px !important;">
    <div class="hero-slider-one m0">
        <div class="item m0"><div class="hero-img" style="background-image: url(/images/assets/example-hero.jpg);"></div></div>
        <div class="item m0"><div class="hero-img" style="background-image: url(/images/media/img_27.jpg);"></div></div>
        <div class="item m0"><div class="hero-img" style="background-image: url(/images/media/img_28.jpg);"></div></div>
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
                                            <option value="sell" >Eladó</option>
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

                            </div>
                            <div class="w-100 d-flex justify-content-end mt-3 input-box-one">
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#detailedSearchCollapse" aria-expanded="false" aria-controls="detailedSearchCollapse">
                                    Részletes keresés
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
-->

<div class="d-block d-md-none hero-banner-three position-relative z-  m-1 d-flex align-items-end justify-content-center pb-5" style="border-radius: 0 !important; min-height: 550px !important;">
	<div class="hero-slider-one m0">
		<div class="item m0"><div class="hero-img" style="background-image: url(/images/assets/example-hero.jpg);"></div></div>
		<div class="item m0"><div class="hero-img" style="background-image: url(/images/media/img_27.jpg);"></div></div>
		<div class="item m0"><div class="hero-img" style="background-image: url(/images/media/img_28.jpg);"></div></div>
	</div>
	<!-- /.hero-slider-one -->
	<div class=" position-relative z-1" style="width:90%">
		<div class="row justify-content-center">
			<div class="col-11 m-auto text-white fs-1 fw-bold" style="text-shadow: 2px 2px 7px rgba(128,128,128,0.83);">
                Találjuk meg együtt új <br> otthonát!
			</div>
            <div class="bg-white rounded-5 w-90">
                                <form action="{{ route('property.list') }}">
									<div class="d-lg-flex gx-0 align-items-center">
										<div class="col">
											<div class="input-box-one">
                                                <div class="switch6 bg-light rounded-3">
                                                    <label class="switch6-light " onclick="">
                                                        <input type="hidden" name="ad_type">
                                                        <input type="checkbox" id="ad_type" {{ request('ad_type') == 'rent' ? 'checked' : '' }}>
                                                        <span>
                                                            <span data-value="sell">Eladó</span>
                                                            <span data-value="rent">Kiadó</span>
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
											<div class="input-box-one">
												<div class="dropdown">
													<button type="button" class="d-flex justify-content-between w-100 align-items-center border rounded-3 px-2 py-1" data-bs-toggle="dropdown">
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
										<div class="d-flex flex-column align-items-center search-box">
											<div class="input-box-one">

                                                    <button type="submit" style="background-color: #96006B !important;" class="fw-500 tran3s rounded-3 py-1 px-2"><i class="bi bi-search" style="font-size: 1.5rem !important; color: #fff"></i></button>

												<div class="mega-dropdown-sm pt-1">
													<button type="button" class="d-flex justify-content-between w-100 align-items-center details-list overflow-auto rounded-3 py-1 px-2" style="border:1px solid #96006B !important;"  data-bs-toggle="dropdown" alt="">
                                                        <i class="bi bi-funnel" style="font-size: 1.5rem !important; color: #96006B"></i>
													</button>
                                                    <div class="dropdown-menu dropdown-menu-lg-end details-box overflow-auto mx-md-5 p-3" style="width: 80vw !important; transform: translate3d(-120px, -51px, 0px) !important;">
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
							<div class="bg-wrapper" style="margin-bottom: 55px !important;">
                                <form action="{{ route('property.list') }}">
									<div class="d-lg-flex gx-0 align-items-center">
										<div class="col">
											<div class="input-box-one">
                                                <div class="switch6 bg-light rounded-3">
                                                    <label class="switch6-light " onclick="">
                                                        <input type="hidden" name="ad_type">
                                                        <input type="checkbox" id="ad_type" {{ request('ad_type') == 'rent' ? 'checked' : '' }}>
                                                        <span>
                                                            <span data-value="sell">Eladó</span>
                                                            <span data-value="rent">Kiadó</span>
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
											<div class="input-box-one">
												<div class="dropdown">
													<button type="button" class="d-flex justify-content-between w-100 align-items-center border rounded-3 px-2 py-1" data-bs-toggle="dropdown">
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
										<div class="d-flex flex-column align-items-center search-box">
											<div class="input-box-one">

                                                    <button type="submit" style="background-color: #96006B !important;" class="fw-500 tran3s rounded-3 py-1 px-2"><i class="bi bi-search" style="font-size: 1.5rem !important; color: #fff"></i></button>

												<div class="mega-dropdown-sm pt-1">
													<button type="button" class="d-flex justify-content-between w-100 align-items-center details-list overflow-auto rounded-3 py-1 px-2" style="border:1px solid #96006B !important;"  data-bs-toggle="dropdown" alt="">
                                                        <i class="bi bi-funnel" style="font-size: 1.5rem !important; color: #96006B"></i>
													</button>
                                                    <div class="dropdown-menu dropdown-menu-lg-end details-box overflow-auto mx-md-5 p-3" style="width: 80vw !important;">
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
                                                            <button type="submit" style="background-color: #96006B !important;" class="fw-500 tran3s rounded-3 py-1 px-2">
                                                                Keresés <i class="bi bi-search" style="font-size: 1.5rem !important; color: #fff"></i>
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
						<!-- /.search-wrapper-one -->
					</div>
				</div>
			</div>
</div>


        <style>
            .switch6 {  max-width: 17em;  margin: 0 auto; }
            .switch6-light > span, .switch-toggle > span {  color: #000000; }
            .switch6-light span span, .switch6-light label, .switch-toggle span span, .switch-toggle label {  color: #2b2b2b; }

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
            .switch6-light a { position: absolute; right: 50%; top: 0px; z-index: 4; display: block; background-color:rgb(218, 215, 215) !important; width: 50%; height: 100%; padding: 0px; border: none;}
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
        <script type="text/javascript">
            $(document).ready(function() {
                $(".active").click(function(event) {
                    $(".active").css('color','white');
                    $(this).css('color','black');
                });
            });
        </script>