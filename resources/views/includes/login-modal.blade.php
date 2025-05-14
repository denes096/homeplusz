<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered">
        <div class="container">
            <div class="user-data-form modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="form-wrapper m-auto">
                    <ul class="nav nav-tabs w-100" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#fc1" role="tab">Bejelentkezés</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#fc2" role="tab">Regisztráció</button>
                        </li>
                    </ul>
                    <div class="tab-content mt-30">
                        <div class="tab-pane show active" role="tabpanel" id="fc1">
                            <div class="text-center mb-20">
                                <h2>Üdv!</h2>
                                <p class="fs-20 color-dark">Még nincs regisztrálva? <a href="#">Regisztráció</a></p>
                            </div>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group-meta position-relative mb-25">
                                            <label>Email*</label>
                                            <input type="email" name="email" placeholder="Youremail@otthonplusz.hu" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="input-group-meta position-relative mb-20">
                                            <label>Jelszó*</label>
                                            <input type="password" name="password" placeholder="Jelszó" class="pass_log_id">
                                            <span class="placeholder_icon"><span class="passVicon"><img src="/images/icon/icon_68.svg" alt=""></span></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="agreement-checkbox d-flex justify-content-between align-items-center">
                                            <div>
                                                <input type="checkbox" id="remember">
                                                <label for="remember">Maradjon bejelentkezve</label>
                                            </div>
                                            <a href="#">Elfelejtett jelszó emlékeztető</a>
                                        </div> <!-- /.agreement-checkbox -->
                                    </div>
                                    <div class="col-12">
                                        <button class="btn-two w-100 text-uppercase d-block mt-20">Bejelentkezés</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" role="tabpanel" id="fc2">
                            <div class="text-center mb-20">
                                <h2>Regisztráció</h2>
                                <p class="fs-20 color-dark">Már van jelszavad? <a href="#">Bejelentkezés</a></p>
                            </div>
                            <form action="#">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group-meta position-relative mb-25">
                                            <label>Név*</label>
                                            <input type="text" placeholder="Név" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="input-group-meta position-relative mb-25">
                                            <label>Email*</label>
                                            <input type="email" placeholder="Youremail@otthonpulsz.hu">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="input-group-meta position-relative mb-20">
                                            <label>Jelszó*</label>
                                            <input type="password" placeholder="Új jelszó" class="pass_log_id">
                                            <span class="placeholder_icon"><span class="passVicon"><img src="/images/icon/icon_68.svg" alt=""></span></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="agreement-checkbox d-flex justify-content-between align-items-center">
                                            <div>
                                                <input type="checkbox" id="remember2" required>
                                                <label for="remember2">A regisztrációval elfogadom az <a href="#">Adatkezelési tájékoztatót</a></label>
                                            </div>
                                        </div> <!-- /.agreement-checkbox -->
                                    </div>
                                    <div class="col-12">
                                        <button class="btn-two w-100 text-uppercase d-block mt-20">Regisztráció</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                    </div>

                    <div class="d-none align-items-center mt-30 mb-10">
                        <div class="line"></div>
                        <span class="pe-3 ps-3 fs-6">Vagy</span>
                        <div class="line"></div>
                    </div>
                    <div class="row d-none">
                        <div class="col-sm-6">
                            <a href="#" class="social-use-btn d-flex align-items-center justify-content-center tran3s w-100 mt-10">
                                <img src="/images/icon/google.png" alt="">
                                <span class="ps-3">Belépés Google fiókommal</span>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="#" class="social-use-btn d-flex align-items-center justify-content-center tran3s w-100 mt-10">
                                <img src="/images/icon/facebook.png" alt="">
                                <span class="ps-3">Belépés Facebook fiókommal</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /.form-wrapper -->
            </div>
            <!-- /.user-data-form -->
        </div>
    </div>
</div>
