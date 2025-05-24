@extends('layouts/homeplus')

@section('searchbar')@endsection

@section('content')
    <div class="listing-details-one theme-details-one bg-white pt-180 lg-pt-150 pb-150 xl-pb-120">
        <div class="container">
            <div class="row">
                <div class="col-xl-8">
                    <div class="property-overview bg-white shadow4 border-20 p-40 mb-50">
                        <h4 class="mb-20">Bemutatkozás</h4>
                        <p class="fs-20 lh-lg">Lorem ipsum dolor sit amet consectetur. Et velit varius ipsum tempor vel dignissim tincidunt. Aliquam accumsan laoreet ultricies tincidunt faucibus fames augue in sociis. Nisl enim integer neque nec.</p>
                    </div>


                    <div class="similar-property">
                        <h4 class="mb-40">Munkatársaink</h4>
                        <div class="similar-listing-slider-one">
                            @foreach($users as $user)
                                @php
                                /** @var \App\Models\User $user */
                                 @endphp
                                <div class="item zoom">
                                    <div class="listing-card-one shadow4 style-three border-30 mb-50">
                                        <div class="img-gallery p-15">
                                            <div class="position-relative border-20 overflow-hidden">
                                                <img src="{{ $user->getProfilePicture() }}" class="w-100 border-20" alt="...">
                                                <a href="{{ route('aboutUs.show-agent-details', [ 'id' => $user->id] ) }}" class="btn-four inverse rounded-circle position-absolute"><i class="bi bi-arrow-up-right"></i></a>
                                            </div>
                                        </div>
                                        <div class="property-info pe-4 ps-4">
                                            <a href="{{ route('aboutUs.show-agent-details', [ 'id' => $user->id] ) }}" class="title tran3s">{{ $user->name }}</a>
                                            <div class="address">{{ $user->position }}</div>
                                            <!--
                                            <div class="pl-footer top-border d-flex align-items-center justify-content-between">
                                                <strong class="price fw-500 color-dark">{{ $user->desctiption ?? 'Leírás röviden' }}</strong>
                                            </div>
                                            -->
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="property-score bg-white shadow4 border-20 p-40 mb-50">

                    </div>

                    <div class="property-location mb-50">
                        <div class="bg-white shadow4 border-20 p-30">
                            <div class="map-banner overflow-hidden border-15">
                                <div class="gmap_canvas h-100 w-100">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2701.0803714100675!2d18.916212876607915!3d47.39086470284989!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4741e168219e903b%3A0x6dc0aa2d7e71c50a!2sOtthon%20Plusz%20Ingatlan!5e0!3m2!1shu!2shu!4v1747858785080!5m2!1shu!2shu" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="w-100 h-100"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="col-xl-4 col-lg-8 me-auto ms-auto">
                    <div class="theme-sidebar-one dot-bg p-30 ms-xxl-3 lg-mt-80">
                        <div class="agent-info bg-white border-20 p-30 mb-40">
                            <div class="text-center mt-25">
                                <h6 class="name">Vegye fel velünk a kapcsolatot!</h6>
                                <ul class="style-none d-flex align-items-center justify-content-center social-icon">
                                    <li><a href="https://www.facebook.com/otthonplusz/?locale=hu_HU"><i class="fa-brands fa-facebook-f"></i></a></li>
                                </ul>
                            </div>
                            <div class="divider-line mt-40 mb-45 pt-20">
                                <ul class="style-none">
                                    <li>Email: <span><a href="mailto:info@otthonplusz.hu">info@otthonplusz.hu</a></span></li>
                                    <li>Telefon: <span><a href="tel:+36205829895">+36-20-582-9895 (Bakó Norbert)</a></span></li>
                                    <li>Telefon: <span><a href="tel:+36209273828">+36-20-927-3828</a></span></li>
                                </ul>
                            </div>
                            <div>
                                <h5>Kérjen személyes bemutatást!</h5>
                                <p>Adja meg adatait, a referencia számo(ka)t és hogy mikor érne rá!</p>
                                <form action="#">
                                    <div class="input-box-three mb-25">
                                        <div class="label">Neve</div>
                                        <input type="text" placeholder="Teljes Neve" class="type-input">
                                    </div>

                                    <div class="input-box-three mb-25">
                                        <div class="label">E-mail címe</div>
                                        <input type="email" placeholder="E-mailcíme" class="type-input">
                                    </div>

                                    <div class="input-box-three mb-25">
                                        <div class="label">Telefonszáma</div>
                                        <input type="tel" placeholder="Telefonszáma" class="type-input">
                                    </div>

                                    <div class="input-box-three mb-15">
                                        <div class="label">Üzenet</div>
                                        <textarea placeholder="Üzenet..."></textarea>
                                    </div>

                                    <button class="btn-nine text-uppercase rounded-3 w-100 mb-10">Küldés</button>
                                </form>

                            </div>
                        </div>
                        <!-- /.agent-info -->

                        <div class="tour-schedule bg-white border-20 p-30 mb-40">
                            <h5 class="mb-40">Segítségre van szüksége?</h5>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
