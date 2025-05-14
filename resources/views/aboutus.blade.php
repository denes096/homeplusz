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
                            <div class="item zoom">
                                <div class="listing-card-one shadow4 style-three border-30 mb-50">
                                    <div class="img-gallery p-15">
                                        <div class="position-relative border-20 overflow-hidden">
                                            <img src="/images/listing/img_13.jpg" class="w-100 border-20" alt="...">
                                            <a href="agent_details.html" class="btn-four inverse rounded-circle position-absolute"><i class="bi bi-arrow-up-right"></i></a>
                                            <div class="img-slider-btn">
                                                03 <i class="fa-regular fa-image"></i>
                                                <a href="/images/listing/img_large_01.jpg" class="d-block" data-fancybox="img1" data-caption="ÉRD"></a>
                                                <a href="/images/listing/img_large_02.jpg" class="d-block" data-fancybox="img1" data-caption="ÉRD"></a>
                                                <a href="/images/listing/img_large_03.jpg" class="d-block" data-fancybox="img1" data-caption="ÉRD"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="property-info pe-4 ps-4">
                                        <a href="listing_01.html" class="title tran3s">Norbert</a>
                                        <div class="address">Tulajdonos</div>
                                        <div class="pl-footer top-border d-flex align-items-center justify-content-between">
                                            <strong class="price fw-500 color-dark">Lorem Ipsum</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                            </div>
                            <div class="item">
                            </div>
                            <div class="item">
                            </div>
                            <div class="item">
                            </div>
                        </div>
                    </div>

                    <div class="property-score bg-white shadow4 border-20 p-40 mb-50">

                    </div>

                    <div class="property-location mb-50">
                        <div class="bg-white shadow4 border-20 p-30">
                            <div class="map-banner overflow-hidden border-15">
                                <div class="gmap_canvas h-100 w-100">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d83088.3595592641!2d-105.54557276330914!3d39.29302101722867!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x874014749b1856b7%3A0xc75483314990a7ff!2sColorado%2C%20USA!5e0!3m2!1sen!2sbd!4v1699764452737!5m2!1sen!2sbd" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="w-100 h-100"></iframe>
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
                                    <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                                    <li><a href="#"><i class="fa-brands fa-linkedin"></i></a></li>
                                </ul>
                            </div>
                            <div class="divider-line mt-40 mb-45 pt-20">
                                <ul class="style-none">
                                    <li>Email: <span><a href="mailto:akabirr770@gmail.com">info@otthonplusz.hu</a></span></li>
                                    <li>Phone: <span><a href="tel:+12347687565">+36301112233</a></span></li>
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
