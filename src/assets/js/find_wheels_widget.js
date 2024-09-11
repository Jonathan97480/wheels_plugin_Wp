
document.addEventListener('DOMContentLoaded', function () {

    //get elements wheels size
    const W = document.getElementById('wheel_width');
    const H = document.getElementById('wheel_height');
    const D = document.getElementById('wheel_diameter');
    const LadIndex = document.getElementById('wheel_lad_index');
    const Speed = document.getElementById('wheel_speed');
    //Filter Variables
    const isRunFlat = document.getElementById('isRunFlat');
    const saison = document.getElementById('saison');
    const typeVehicule = document.getElementById('Vehicule');
    const brand = document.getElementById('bran_list');

    //get elements select input wheels
    const selectW = document.getElementById('select_wheel_width');
    const selectH = document.getElementById('select_wheel_height');
    const selectD = document.getElementById('select_wheel_diameter');
    const selectLadIndex = document.getElementById('select_wheel_lad_index');
    const selectSpeed = document.getElementById('select_wheel_speed');

    //change color if input select selected
    selectW.addEventListener('click', function () {
        resetColor();
        W.style.color = 'green';
        W.classList.add('before_circle');



    });

    selectW.addEventListener('change', function () {
        W.innerHTML = selectW.value;
        resetColor()


    });

    selectH.addEventListener('click', function () {
        resetColor();
        H.style.color = 'green';
        H.classList.add('before_circle');
    });

    selectH.addEventListener('change', function () {
        H.innerHTML = selectH.value;
        resetColor()
    }
    );

    selectD.addEventListener('click', function () {
        resetColor();
        D.style.color = 'green';
        D.classList.add('before_circle');
    });

    selectD.addEventListener('change', function () {
        D.innerHTML = selectD.value;
        resetColor()
    });

    selectLadIndex.addEventListener('click', function () {
        resetColor();
        LadIndex.style.color = 'green';
        LadIndex.classList.add('before_circle');
    });

    selectLadIndex.addEventListener('change', function () {
        LadIndex.innerHTML = selectLadIndex.value;
        resetColor()
    });

    selectSpeed.addEventListener('click', function () {
        resetColor();
        Speed.style.color = 'green';
        Speed.classList.add('before_circle');
    });

    selectSpeed.addEventListener('change', function () {
        Speed.innerHTML = selectSpeed.value;
        resetColor()
    });







    //rezet color
    function resetColor() {
        console.log('reset color');
        W.style.color = 'white';
        H.style.color = 'white';
        D.style.color = 'white';
        LadIndex.style.color = 'white';
        Speed.style.color = 'white';

        //remove class
        W.classList.remove('before_circle');
        H.classList.remove('before_circle');
        D.classList.remove('before_circle');
        LadIndex.classList.remove('before_circle');
        Speed.classList.remove('before_circle');
    }


    /* Form event submit */
    document.getElementById('form_find_wheels').addEventListener('submit', function (e) {
        e.preventDefault();

        //get values
        const wheel_width = selectW.value;
        const wheel_height = selectH.value;
        const wheel_diameter = selectD.value;
        const wheel_lad_index = selectLadIndex.value;
        const wheel_speed = selectSpeed.value;

        //get url form
        const url = e.target.getAttribute('action');

        /* define params url */
        const params = new URLSearchParams();
        params.append('W', wheel_width);
        params.append('H', wheel_height);
        params.append('D', wheel_diameter);
        params.append('Lad_index', wheel_lad_index);
        params.append('Speed', wheel_speed);
        /* add filter params */
        params.append('is_runflat', isRunFlat.value);
        params.append('saison', saison.value);
        params.append('typeVehicule', typeVehicule.value);
        params.append('brand', brand.value);

        //redirect
        window.location.href = url + '?' + params.toString();

    });

    /* Open filter block */
    document.getElementById('open_filter').addEventListener('click', function () {
        document.getElementById('filter_block').classList.toggle('filter-show');
    });


}); // This is the same as jQuery(document).ready(function ($) {});