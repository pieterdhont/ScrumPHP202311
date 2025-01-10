<?php
$huidigBestand = $_SERVER["PHP_SELF"];
$delenBestandnaam = explode('/', $huidigBestand);
$huidigePagina = end($delenBestandnaam);

if (isset($_COOKIE['gebruiker'])) {
    $gebruikerHeader = unserialize($_COOKIE['gebruiker']);
} else {
    $gebruikerHeader = '';
}

// Test:
// $gebruiker = 'Elias';
?>

<!DOCTYPE html>
<html lang="nl">

<head> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/header.css">
    <script src="./JavaScript/header.js"></script>
    <link defer rel="stylesheet" href="css/dark.css">
    <script defer src="./JavaScript/dark.js"></script>
    <link rel="icon" href="./assets/prulariaicon.ico">
    <link rel="stylesheet" href="css/cookies.css">
</head>

<body>
    <header>
        <div id="webshopLogoDiv">
            <a id="webshopLogoLink" href="overzicht.php">
                <img id="webshopLogo" src="./assets/logo_prularia.png" alt="Logo prularia">
            </a>
        </div>
        <nav id="navHeader">
            <ul>
                <li><a href="overzicht.php"
                        class="<?php echo ($huidigePagina === 'overzicht.php') ? 'linkHeader actievePagina' : 'linkHeader'; ?>">Home</a>
                </li>
                <li><a href="faq.php"
                        class="<?php echo ($huidigePagina === 'faq.php') ? 'linkHeader actievePagina' : 'linkHeader'; ?>">FAQ</a>
                </li>
                <li><a href="contact.php"
                        class="<?php echo ($huidigePagina === 'contact.php') ? 'linkHeader actievePagina' : 'linkHeader'; ?>">Contact</a>
                </li>
            </ul>
        </nav>

        <div id="gebruikerEnMenusHeader">
            <div id="gebruikerHeader">
                <a id="gebruikerTekst" href="gebruiker.php"><?php echo ($gebruikerHeader != '') ? 'Welkom, ' . $gebruikerHeader->getVoornaam() : 'Inloggen'; ?></a>
            </div>
            <div id="menusHeader">
                <img class="menuHeaderAfbeelding" id="toegankelijkheidToggle" src="./assets/toegankelijkheid.PNG"
                    alt="toegankelijkheid" tabindex="0">
                <img class="menuHeaderAfbeelding" id="wishlistToggle" src="./assets/wishlist.PNG" alt="wishlist" tabindex="0">
                <div id="winkelmandToggleContainer">
                    <img class="menuHeaderAfbeelding" id="winkelmandToggle" src="./assets/winkelmand.PNG"
                        alt="winkelmandje" tabindex="0">
                    <span class="badge" id="winkelmandBadge">0</span>
                </div>
            </div>
        </div>
        <div id="mobileMenu" onclick="toggleMenu()">
            <img id="mobileMenuImage" src="assets/menu.PNG"></img>
        </div>
    </header>
    <div class="popup_container">
    <div class="popupMenu verborgen" id="wishlistDiv">
        <p>Er is nog niets toegevoegd aan jouw wishlist.</p>
    </div>
    <div class="popupMenu verborgen" id="winkelmandDiv">
        <?php include 'includes/winkelmand.php' ?>
    </div>
    <div class="popupMenu verborgen" id="toegankelijkheidDiv">
        <ul>
            <li><button id="schakelknopLettertype" class="schakelknopLettertype"></button></li>
            <li><button id="themeToggle" class="themeToggle"></button></li>
        </ul>
    </div>
    </div>