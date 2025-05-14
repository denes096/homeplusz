document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.label-button');
    const sliders = document.querySelectorAll('.property-slider-container');

    // Rejtse el az összeset, kivéve az elsőt
    sliders.forEach((slider, index) => {
        slider.style.display = (index === 0) ? '' : 'none';
    });

    // Gombkattintás események
    buttons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const selectedLabel = button.getAttribute('data-label');

            // Gomb aktívvá tétele
            buttons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            // Megfelelő slider megjelenítése
            sliders.forEach(slider => {
                if (slider.getAttribute('data-label') === selectedLabel) {
                    slider.style.display = '';
                    $('.listing-slider-one').slick('setPosition');
                } else {
                    slider.style.display = 'none';
                }
            });
        });
    });
});
$(document).ready(function () {
    $('#ad_type').on('change', function () {
        if ($(this).is(':checked')) {
            $('input[name="ad_type"]').val('rent');
        } else {
            $('input[name="ad_type"]').val('sell');
        }
    });

    // inicializáláskor is állítsuk be
    $('#ad_type').trigger('change');

    document.querySelector('.search-for-code').addEventListener('click', function () {
        let code = document.getElementById('search-code').value.trim();
        if (code) {
            window.location.href = '/ingatlan/kod/' + encodeURIComponent(code);
        }
    });
});
