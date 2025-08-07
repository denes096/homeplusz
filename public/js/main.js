document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.label-button');
    const propCols = document.querySelectorAll('.property-col');
    // Gombkattintás események
    buttons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const selectedLabel = button.getAttribute('data-label');


            // Gomb aktívvá tétele
            buttons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            // Megfelelő slider megjelenítése
            propCols.forEach(slider => {
                if (selectedLabel === 'all') {
                    slider.style.display = '';
                } else {
                    if (slider.getAttribute('data-label') === selectedLabel) {
                        slider.style.display = '';
                    } else {
                        slider.style.display = 'none';
                    }
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

    const input = document.getElementById('search-code');
    const button = document.querySelector('.search-for-code');

    input.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' && input.value.trim() !== '') {
            event.preventDefault(); // Megakadályozza az űrlap valódi submitját (ha van ilyen)
            button.click();
        }
    });
});
