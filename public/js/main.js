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
