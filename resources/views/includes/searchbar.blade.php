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

<div class="hero-banner-three position-relative z-1  m-1 d-flex align-items-end justify-content-center" style="border-radius: 0 !important; min-height: 550px !important;">
			<div class="hero-slider-one m0">
				<div class="item m0"><div class="hero-img" style="background-image: url(images/assets/example-hero.jpg);"></div></div>
				<div class="item m0"><div class="hero-img" style="background-image: url(images/media/img_27.jpg);"></div></div>
				<div class="item m0"><div class="hero-img" style="background-image: url(images/media/img_28.jpg);"></div></div>
			</div>
			<!-- /.hero-slider-one -->
			<div class=" position-relative z-2">
				<div class="row">
					<div class="col-lg-10 m-auto">
					</div>
				</div>
				<div class="row">
					<div class="col-11 m-auto">
						<div class="search-wrapper-one layout-one position-relative wow fadeInUp" data-wow-delay="0.2s">
							<div class="bg-wrapper" style="margin-bottom: 55px !important;">
								<form action="listing_01.html">
									<div class="d-lg-flex gx-0 align-items-center">
										<div class="col">
											<div class="input-box-one border-left">
                                                <div class="switch6 bg-light rounded-pill">
                                                    <label class="switch6-light " onclick="">
                                                            <input type="checkbox">
                                                            <span>
                                                                <span>Eladó</span>
                                                                <span>Kiadó</span>
                                                            </span>
                                                            <a class="btn bg-theme rounded-pill active"></a>
                                                    </label>
                                                </div>

											</div>
											<!-- /.input-box-one -->
										</div>
										<div class="col">
											<div class="input-box-one border-left">
												<div class="dropdown">
													<button type="button" class="d-flex justify-content-between w-100 align-items-center" data-bs-toggle="dropdown">
														<input type="text" class="border-0" style="width: 60% !important" placeholder="Hol keres.."> <img src="images/icon/rooms-icon.png" width="15" height="15" alt="rooms-icon">
													</button>
													<ul class="dropdown-menu city-list overflow-auto">
													  <li class="d-flex flex-nowrap">
                                                        <a href="">
														<input class="form-check-input" type="checkbox" value="" id="Checkme3" />
														<label class="form-check-label" style="margin-left: 10px !important;"  for="Checkme3">Érd</label>
                                                        </a>
													  </li>
													  <li class="d-flex flex-nowrap">
														<input class="form-check-input" type="checkbox" value="" id="Checkme3" />
														<label class="form-check-label" style="margin-left: 10px !important;" for="Checkme3">Tárnok</label>
													  </li>
													</ul>
												  </div>
											</div>
											<!-- /.input-box-one -->
										</div>
										<div class="col">
											<div class="input-box-one border-left">
												<div class="dropdown">
													<button type="button" class="d-flex justify-content-between w-100 align-items-center" data-bs-toggle="dropdown">
                                                    Típus.. <img src="images/icon/rooms-icon.png" width="15" height="15" alt="rooms-icon">
													</button>
													<ul class="dropdown-menu type-list overflow-auto">
													  <li>
														<input class="form-check-input" type="checkbox" value="" id="Checkme3" />
														<label class="form-check-label" for="Checkme3">Ház</label>
													  </li>
													  <li>
														<input class="form-check-input" type="checkbox" value="" id="Checkme3" />
														<label class="form-check-label" for="Checkme3">Lakás</label>
													  </li>
													  <li>
														<input class="form-check-input" type="checkbox" value="" id="Checkme3" />
														<label class="form-check-label" for="Checkme3">Telek</label>
													  </li>
													</ul>
												  </div>
											</div>
											<!-- /.input-box-one -->
										</div>
										<div class="col">
											<div class="input-box-one border-left">
												<div class="d-flex justify-content-between align-items-center">
													<label for="roomNumber location">Szoba nappalival</label>
													<img src="images/icon/rooms-icon.png" width="15" height="15" alt="rooms-icon">
												</div>
												<div class="d-flex">
													<input type="number" class="col-6"> 
													-
													<input type="number" class="col-6">
												</div>
											</div>
											<!-- /.input-box-one -->
										</div>
										<div class="col">
											<div class="input-box-one border-left">
												<div class="d-flex justify-content-between align-items-center">
													<label for="roomNumber location">Méret</label>
													<img src="images/icon/size-icon.png" width="15" height="15" alt="size-icon">
												</div>
												<div class="d-flex">
													<input type="number" class="col-6"> 
													-
													<input type="number" class="col-6">
												</div>
											</div>
											<!-- /.input-box-one -->
										</div>
										<div class="col">
											<div class="input-box-one border-left border-lg-0">
												<div class="d-flex justify-content-between align-items-center">
													<label for="roomNumber location">Ár</label>
													<img src="images/icon/price-icon.png" width="15" height="15" alt="price-icon">
												</div>
												<div class="d-flex">
													<input type="number" class="col-6"> 
													-
													<input type="number" class="col-6">
												</div>
											</div>
											<!-- /.input-box-one -->
										</div>
										<div class="col">
											<div class="input-box-one border-left">
												<div class="dropdown">
													<button type="button" class="d-flex justify-content-between w-100 align-items-center details-list overflow-auto" data-bs-toggle="dropdown">
														Részletes keresés
													</button>
													<ul class="dropdown-menu p-3">
													  <li class="d-flex flex-nowrap">

													  </li>
													  <li class="d-flex flex-nowrap">

													  </li>
													  <li class="d-flex flex-nowrap">

													  </li>
													</ul>
												  </div>
											</div>
											<!-- /.input-box-one -->
										</div>
										<div class="col">
											<div class="input-box-one lg-mt-10">
												<button class="fw-500 w-100 tran3s search-btn-three">Keresés</button>
											</div>
											<!-- /.input-box-one -->
										</div>
									</div>
                                    <div class="form-check d-flex justify-content-start ms-4">
										<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
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
            .switch6-light a { position: absolute; right: 50%; top: 0px; z-index: 4; display: block; background-color: #96006B !important; width: 50%; height: 100%; padding: 0px;}
            .form-check-input:checked {background-color: #96006B; border-color: #96006B; border-radius: 50% !important}
            .city-list, .type-list{position: relative !important; overflow: scroll; height: 100px;}
            .details-list{position: relative !important; overflow: scroll; max-height: 200px;}
            .search-wrapper-one .bg-wrapper{padding-top: 25px !important; padding-bottom:15px !important}
        </style>