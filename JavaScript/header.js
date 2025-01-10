// Header JavaScript ============================================

// Functie om de waarde van de cookie in te stellen
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// Functie om de waarde van de cookie op te halen
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function verbergAllePopups(uitgeslotenElement) {
    const popups = document.querySelectorAll('.popupMenu');
    popups.forEach(popup => {
        if (popup !== uitgeslotenElement && !popup.classList.contains('verborgen')) {
            popup.classList.add('verborgen');
        };
    });
    const toggles = document.querySelectorAll('.menuHeaderAfbeelding');
    toggles.forEach(toggle => {
        if (toggle.classList.contains('actief')) {
            toggle.classList.remove('actief');
        };
    });
}

function controleWinkelmandInhoud() {
    const tabel = document.getElementById("selectie");
    const aantalRijen = tabel.getElementsByTagName("tr").length;
    if (aantalRijen > 2) {
        return true;
    } else {
        return false;
    }
}

// Hamburger-icon om deel header te tonen

function toggleMenu() {
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });

    var gebruikerEnMenusHeader = document.getElementById('gebruikerEnMenusHeader');
    var navHeader = document.getElementById('navHeader');
    var mobileMenuImage = document.getElementById('mobileMenuImage');

    if (gebruikerEnMenusHeader.style.display === 'none' || gebruikerEnMenusHeader.style.display === '') {
        gebruikerEnMenusHeader.style.display = 'flex';
        navHeader.style.display = 'flex';
        mobileMenuImage.src = './assets/sluiten.PNG';
    } else {
        gebruikerEnMenusHeader.style.display = 'none';
        navHeader.style.display = 'none';
        mobileMenuImage.src = './assets/menu.PNG';
    }
}

function controleerBreedteScherm() {
    var breedteScherm = window.innerWidth;

    var mobileMenuImage = document.getElementById('mobileMenuImage');

    var gebruikerEnMenusHeader = document.getElementById('gebruikerEnMenusHeader');
    var navHeader = document.getElementById('navHeader');

    if (breedteScherm > 600) {
        gebruikerEnMenusHeader.style.display = 'flex';
        navHeader.style.display = 'flex';
        mobileMenuImage.src = './assets/sluiten.PNG';
    }
    else {
        gebruikerEnMenusHeader.style.display = 'none';
        navHeader.style.display = 'none';
        mobileMenuImage.src = './assets/menu.PNG';
    }
}

window.addEventListener('resize', function () {
    controleerBreedteScherm();
});

document.addEventListener("DOMContentLoaded", function () {

    // Pop up's zichtbaar en onzichtbaar maken -------------------------------

    const wishlistToggle = document.getElementById("wishlistToggle");
    const wishlistDiv = document.getElementById("wishlistDiv");

    wishlistToggle.addEventListener("click", function () {
        if (wishlistDiv.classList.contains("verborgen")) {
            verbergAllePopups(wishlistDiv);
            wishlistDiv.classList.remove("verborgen");
            wishlistToggle.classList.add("actief");
        } else {
            wishlistDiv.classList.add("verborgen");
            wishlistToggle.classList.remove("actief");
        }
    });

    const winkelmandToggle = document.getElementById("winkelmandToggle");
    const winkelmandDiv = document.getElementById("winkelmandDiv");

    winkelmandToggle.addEventListener("click", function () {
        if (controleWinkelmandInhoud() === true && winkelmandDiv.classList.contains("verborgen")) {
            verbergAllePopups(winkelmandDiv);
            winkelmandDiv.classList.remove("verborgen");
            winkelmandToggle.classList.add("actief");
        } else {
            winkelmandDiv.classList.add("verborgen");
            winkelmandToggle.classList.remove("actief");
        }
    }

    );

    const toegankelijkheidToggle = document.getElementById("toegankelijkheidToggle");
    const toegankelijkheidDiv = document.getElementById("toegankelijkheidDiv");

    toegankelijkheidToggle.addEventListener("click", function () {
        if (toegankelijkheidDiv.classList.contains("verborgen")) {
            verbergAllePopups(toegankelijkheidDiv);
            toegankelijkheidDiv.classList.remove("verborgen");
            toegankelijkheidToggle.classList.add("actief");
        } else {
            toegankelijkheidDiv.classList.add("verborgen");
            toegankelijkheidToggle.classList.remove("actief");
        }
    });

    // Icoon mobileMenu veranderen bij het scrollen

    window.addEventListener('scroll', function () {
        var mobileMenuImage = document.getElementById('mobileMenuImage');
        var scrolled = window.scrollY;

        if (scrolled > 0) {
            //Begin scrollen
            mobileMenuImage.src = 'assets/omhoog.PNG';
        } else {
            //Bovenaan (+ menu openen)

            gebruikerEnMenusHeader.style.display = 'flex';
            navHeader.style.display = 'flex';

            mobileMenuImage.src = './assets/sluiten.PNG';
        }
    });

    // Schakelknop open dyslexic --------------------------

    var schakelknopLettertype = document.getElementById("schakelknopLettertype");

    // Haal de dyslexic mode status op uit de cookie
    var dyslexicLettertype = getCookie("dyslexicLettertype") === "true";

    // Pas de dyslexic mode status toe bij het laden van de pagina
    document.body.classList.toggle("dyslexic-font", dyslexicLettertype);
    schakelknopLettertype.classList.toggle("dyslexic-mode", dyslexicLettertype);
    schakelknopLettertype.classList.toggle("groene-knop", dyslexicLettertype);

    schakelknopLettertype.addEventListener("click", function () {

        // Haal alle teksttags op (p, h1, a, enz.)
        var tekstElementen = document.querySelectorAll("p, h1, h2, h3, h4, h5, h6, a, label, span, div, li, td, form, input[type='text'], button, select, textarea");

        // Toggle de klasse voor elk tekstelement
        tekstElementen.forEach(function (element) {
            element.classList.toggle("dyslexic-font");
        });

        // Toggle de klasse voor de body
        document.body.classList.toggle("dyslexic-font");

        // Toggle de klasse voor de knop zelf
        schakelknopLettertype.classList.toggle("dyslexic-mode");

        // Verander de kleur van de knop
        schakelknopLettertype.classList.toggle("groene-knop");

        // Sla de status van dyslexic mode op in een cookie
        var dyslexicMode = schakelknopLettertype.classList.contains("dyslexic-mode");
        setCookie("dyslexicLettertype", dyslexicMode.toString(), 365);

        // Update de knopstijl na het inschakelen van dyslexic mode
        schakelknopLettertype.classList.toggle("groene-knop", dyslexicMode);
    });



});
