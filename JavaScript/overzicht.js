"use strict";

function toggleCategorieMenu() {
    var menu = document.getElementById('categorieen');
    if (menu.style.display === 'none' || menu.style.display === '') {
        menu.style.display = 'block';
    } else {
        menu.style.display = 'none';
    }

    var img = document.getElementById('categorieKnopImg');
    if (img.src.includes('symboolBeneden.PNG')) {
        img.src = './assets/sluitenBold.PNG';
    } else {
        img.src = './assets/symboolBeneden.PNG';
    }
}

function controleerBreedteSchermOverzicht() {
    var breedteScherm = window.innerWidth;

    var categorieen = document.getElementById('categorieen');
    var categorieKnop = document.getElementById('categorieKnopDiv');
    var categorieKnopImg = document.getElementById('categorieKnopImg');

    if (breedteScherm > 600) {
        categorieen.style.display = 'block';
        categorieKnop.style.display = 'none';
        categorieKnopImg.src = './assets/sluitenBold.PNG';
    }
    else {
        categorieen.style.display = 'none';
        categorieKnop.style.display = 'flex';
        categorieKnopImg.src = './assets/symboolBeneden.PNG';
    }
}

document.addEventListener("DOMContentLoaded", function () {

    var categorieKnopDiv = document.getElementById('categorieKnopDiv');

    categorieKnopDiv.addEventListener('click', function () {
        toggleCategorieMenu();
    });

    // Huidige parameters en pijlen ophalen

    var huidigePagina;

    var huidigeParams = window.location.search;

    var pijlRechts = document.querySelector(".rechts");
    var pijlLinks = document.querySelector(".links");

    var voorbijPagina1 = huidigeParams.indexOf('?pagina=') !== -1;    // Controle of GET_$ pagina aanwezig is in de huidige parameters

    if (voorbijPagina1) {
         //  parameter "?pagina=" is present.

        var paginaNummerRegex = /\?pagina=(\d+)/;   //Regular expression voor de zoekopdracht
        var match = huidigeParams.match(paginaNummerRegex); //Zoeken naar een match in de parameters
        var huidigePagina = parseInt(match[1]);   //Regular expression index 1 = digit van pagina nummer
       
        var huidigeParamsZonderPagina = huidigeParams.replace(paginaNummerRegex, '');   //"?pagina=*nummer*" uit de parameters knippen 
        var huidigeParamsZonderPagina = huidigeParamsZonderPagina.replace('?', '&');   // Eventuele "?" door "&" vervangen

        // Pijlen href aanpassen naar correcte pagina + andere geselecteerde parameters
        pijlRechts.href = 'overzicht.php?pagina=' + (huidigePagina + 1) + huidigeParamsZonderPagina;
        pijlLinks.href = 'overzicht.php?pagina=' + (huidigePagina - 1) + huidigeParamsZonderPagina;

    } else {
        //  Momenteel op pagina 1

        huidigePagina = 1;

        var huidigeParams = huidigeParams.replace('?', '&');   // Eventuele "?" door "&" vervangen

        // Pijl rechts href aanpassen (links niet nodig vanwege, want paginanummer is 1)
        pijlRechts.href = 'overzicht.php?pagina=' + (huidigePagina + 1) + huidigeParams;
    }

    console.log("Paginanummer in JS: ", huidigePagina);
    
    window.addEventListener('resize', function () {
        controleerBreedteSchermOverzicht();
    });

});

// Wisknop om zoekresultaten te wissen ----------------

const wisKnop = document.getElementById('wisZoekResultaten');
if (wisKnop) {
    wisKnop.addEventListener('click', function() {
        window.location.href = 'overzicht.php';
    });
}

$(document).ready(function(){
    $('.artikels_slider').slick({
        accessibility: true,
        dots: true,
        infinite: true,
        speed: 300,
        slidesToShow: 3,
        centerMode: true,
        variableWidth: true,
        prevArrow: '<a href="#" class="prev"><p>&#10094;</p></a>',
        nextArrow: '<a href="#" class="next"><p>&#10095;</p></a>'
    });
    });