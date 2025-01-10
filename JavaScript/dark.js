// common.js

document.addEventListener('DOMContentLoaded', function () {
    const themeToggle = document.getElementById('themeToggle');
    const body = document.body;

    themeToggle.addEventListener('click', toggleDarkMode);

    // Controleer de voorkeur voor donkere modus in localStorage
    const isDarkModePreferred = checkDarkModePreference();

    if (isDarkModePreferred) {
        applyDarkMode();
    }

    // Werk de kleur van de donkere modusknop bij bij het laden van de pagina
    updateDarkModeButton(isDarkModePreferred);

    function toggleDarkMode() {
        body.classList.toggle('dark-mode');
        const isDarkMode = body.classList.contains('dark-mode');
        themeToggle.classList.toggle("groene-knop");
        const logo = document.getElementById('webshopLogo');
        const toegankelijkheid = document.getElementById('toegankelijkheidToggle');
        const wishlist = document.getElementById('wishlistToggle');
        const winkelmand = document.getElementById('winkelmandToggle');
        const mobileMenuImage = document.getElementById('mobileMenuImage');
        const artikelImgVoorbeeld = document.querySelectorAll('.fotoContainer img');



        // Werk de kleur van de donkere modusknop bij
        updateDarkModeButton(isDarkMode);

        // Sla de voorkeur voor donkere modus op in localStorage
        saveDarkModePreference(isDarkMode);

        // Zwarte Fotos vervangen met witte
        if (isDarkMode) {
            logo.src = './assets/logo_prularia_wit.png';
            toegankelijkheid.src = './assets/toegankelijkheid_wit.png';
            wishlist.src = './assets/wishlist_wit.png';
            winkelmand.src = './assets/winkelmand_wit.png';
            mobileMenuImage.src = 'assets/menu_wit.png';
            for (const img of artikelImgVoorbeeld) {
                if (img.src.search("artikelVoorbeeldImg.png") !== -1)
                    img.src = 'assets/thumbnailArtikels/artikelVoorbeeldImg_wit.png';
            }
        }
        else {
            logo.src = './assets/logo_prularia.png';
            toegankelijkheid.src = './assets/toegankelijkheid.PNG';
            wishlist.src = './assets/wishlist.PNG';
            winkelmand.src = './assets/winkelmand.PNG';
            mobileMenuImage.src = 'assets/menu.PNG';
            for (const img of artikelImgVoorbeeld) {
                if (img.src.search("artikelVoorbeeldImg_wit.png") !== -1)
                    img.src = 'assets/thumbnailArtikels/artikelVoorbeeldImg.png';
            }
        }
    }

    function updateDarkModeButton(isDarkMode) {
        const darkModeButton = document.getElementById("themeToggle");

        // Stel de kleur van de knop in op basis van de donkere modus
        darkModeButton.classList.toggle("groene-knop", isDarkMode);
    }

    function checkDarkModePreference() {
        // Controleer in localStorage, gebruik anders cookies
        const darkModePreference = localStorage.getItem('darkMode') || getDarkModeCookie();
        return darkModePreference === '1';
    }

    function getDarkModeCookie() {
        const darkModeCookie = document.cookie.split(';').find(cookie => cookie.trim().startsWith('darkMode='));
        return darkModeCookie ? darkModeCookie.split('=')[1] : '0';
    }

    function saveDarkModePreference(isDarkMode) {
        // Sla de voorkeur voor donkere modus op in localStorage
        localStorage.setItem('darkMode', isDarkMode ? '1' : '0');
    }

    function applyDarkMode() {
        body.classList.add('dark-mode');
        const isDarkMode = body.classList.contains('dark-mode');
        const logo = document.getElementById('webshopLogo');
        const toegankelijkheid = document.getElementById('toegankelijkheidToggle');
        const wishlist = document.getElementById('wishlistToggle');
        const winkelmand = document.getElementById('winkelmandToggle');
        const mobileMenuImage = document.getElementById('mobileMenuImage');
        const artikelImgVoorbeeld = document.querySelectorAll('.fotoContainer img');

        if (isDarkMode) {
            logo.src = './assets/logo_prularia_wit.png';
            toegankelijkheid.src = './assets/toegankelijkheid_wit.png';
            wishlist.src = './assets/wishlist_wit.png';
            winkelmand.src = './assets/winkelmand_wit.png';
            mobileMenuImage.src = 'assets/menu_wit.png';
            for (const img of artikelImgVoorbeeld) {
                if (img.src.search("artikelVoorbeeldImg.png") !== -1)
                    img.src = 'assets/thumbnailArtikels/artikelVoorbeeldImg_wit.png';
            }
        }
        else {
            logo.src = './assets/logo_prularia.png';
            toegankelijkheid.src = './assets/toegankelijkheid.PNG';
            wishlist.src = './assets/wishlist.PNG';
            winkelmand.src = './assets/winkelmand.PNG';
            mobileMenuImage.src = 'assets/menu.PNG';
            for (const img of artikelImgVoorbeeld) {
                if (img.src.search("artikelVoorbeeldImg_wit.png") !== -1)
                    img.src = 'assets/thumbnailArtikels/artikelVoorbeeldImg.png';
            }
        }
    }
});
