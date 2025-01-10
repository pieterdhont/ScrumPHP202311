<?php
declare(strict_types=1);
require_once('data/autoloader.php');
session_start();

//  als de gebruiker niet ingelogd is, stuur deze door naar de inlogpagina
if (!isset($_COOKIE['gebruiker']) || $_COOKIE['gebruiker'] == '') {
    header('Location: inloggen.php');
    exit(0);
}

//  Verwijder de gebruiker cookie als de klant uitlogt
if (isset($_GET['action']) && $_GET['action'] == 'loguit') {
    //verwijder gebruiker cookie en redirect
    unset($_COOKIE['gebruiker']);
    setcookie("gebruiker", "", time() - 3600);

    header('Location: gebruiker.php');
    exit(0);
}

//Declareer de nodige services
$gebruikerSvc = new GebruikerService();
$adresSvc = new AdresService();
$bestellingSvc = new BestellingService();
$artikelSvc = new ArtikelService();

//Haal het gebruiker object op uit de cookie
$gebruiker = unserialize($_COOKIE['gebruiker']);

//Haal de bestelgeschiedenis op van de klant
$gebruiker_bestelbonnen = $bestellingSvc->getBestellingenByKlantId(intval($gebruiker->getKlantId()));

if (isset($_GET['action']) && $_GET['action'] == 'wijzigfactuuradres') {
    $factuur_straat = $_POST['factuur_straat'];
    $factuur_huisnummer = $_POST['factuur_huisnummer'];
    $factuur_bus = $_POST['factuur_bus'];
    $factuur_postcode = $_POST['factuur_postcode'];
    $factuur_plaats = $_POST['factuur_plaats'];

    $geldigePostcode = true;

    //Kijk als het factuuradres al bestaat in de database
    $factuurAdresId = $adresSvc->controleerAlsAdresBestaat($factuur_straat, $factuur_huisnummer, $factuur_bus, $factuur_postcode, $factuur_plaats);
    //Als het adres niet bestaat (0 is returned), voeg het toe aan de database
    if ($factuurAdresId == 0) {
        if ($adresSvc->addAdres($factuur_straat, $factuur_huisnummer, $factuur_bus, $factuur_postcode, $factuur_plaats) == false) {
            $geldigePostcode = false;
        }
        $factuurAdresId = $adresSvc->controleerAlsAdresBestaat($factuur_straat, $factuur_huisnummer, $factuur_bus, $factuur_postcode, $factuur_plaats);
    }

    if ($geldigePostcode) {
        $gebruikerSvc->wijzigFactuurAdres(intval($gebruiker->getKlantId()), $factuurAdresId);
        //update de huidige cookie met de nieuwe gegevens
        $nieuwe_gebruiker = $gebruikerSvc->getGebruikerByEmailadres($gebruiker->getEmailadres());
        setcookie('gebruiker', serialize($nieuwe_gebruiker));
        $_SESSION['feedback'] = 'Facturatieadres gewijzigd.';
        $_SESSION['feedback_color'] = 'green';
    } else {
        $_SESSION['feedback'] = 'Dit is geen geldige postcode.';
        $_SESSION['feedback_color'] = 'red';

    }
    header('Location: gebruiker.php');
    exit(0);
}

if (isset($_GET['action']) && $_GET['action'] == 'wijzigleveradres') {
    $lever_straat = $_POST['lever_straat'];
    $lever_huisnummer = $_POST['lever_huisnummer'];
    $lever_bus = $_POST['lever_bus'];
    $lever_postcode = $_POST['lever_postcode'];
    $lever_plaats = $_POST['lever_plaats'];

    $geldigePostcode = true;

    //Kijk als het leveradres al bestaat in de database
    $leverAdresId = $adresSvc->controleerAlsAdresBestaat($lever_straat, $lever_huisnummer, $lever_bus, $lever_postcode, $lever_plaats);

    //Als het leveradres niet bestaat (0 is returned), voeg het toe aan de database
    if ($leverAdresId == 0) {
        if ($adresSvc->addAdres($lever_straat, $lever_huisnummer, $lever_bus, $lever_postcode, $lever_plaats) == false) {
            $geldigePostcode = false;
        }
        $leverAdresId = $adresSvc->controleerAlsAdresBestaat($lever_straat, $lever_huisnummer, $lever_bus, $lever_postcode, $lever_plaats);
    }

    if ($geldigePostcode) {
        $gebruikerSvc->wijzigLeverAdres(intval($gebruiker->getKlantId()), $leverAdresId);
        //update de huidige cookie met de nieuwe gegevens
        $nieuwe_gebruiker = $gebruikerSvc->getGebruikerByEmailadres($gebruiker->getEmailadres());
        setcookie('gebruiker', serialize($nieuwe_gebruiker));

        $_SESSION['feedback'] = 'Leveradres gewijzigd.';
        $_SESSION['feedback_color'] = 'green';
        header('Location: gebruiker.php');
        exit(0);
    } else {
        $_SESSION['feedback'] = 'Dit is geen geldige postcode.';
        $_SESSION['feedback_color'] = 'red';
    }






}

if (isset($_GET['action']) && $_GET['action'] == 'wijzigbedrijfgegevens') {
    //Voeg bedrijfsgegevens toe aan database en verander bedrijfgegevens van de klant
    $gebruikerSvc->wijzigBedrijfsGegevens($gebruiker->getKlantId(), $_POST['klant_bedrijfsnaam'], $_POST['klant_BTWNummer']);

    //update de huidige cookie met de nieuwe gegevens
    $nieuwe_gebruiker = $gebruikerSvc->getGebruikerByEmailadres($gebruiker->getEmailadres());
    setcookie('gebruiker', serialize($nieuwe_gebruiker));

    $_SESSION['feedback'] = 'Bedrijfsgegevens gewijzigd.';
    $_SESSION['feedback_color'] = 'green';
    header('Location: gebruiker.php');
    exit(0);
}

if (isset($_GET['action']) && $_GET['action'] == 'wijzigpaswoord') {
    if ($_POST['paswoord'] == $_POST['paswoord2']) {
        $gebruikerSvc->wijzigWachtwoord($gebruiker->getGebruikersAccountId(), $_POST['paswoord']);
        $_SESSION['feedback'] = 'Wachtwoord gewijzigd.';
        $_SESSION['feedback_color'] = 'green';
    } else {
        $_SESSION['feedback'] = 'De gegeven wachtwoorden komen niet overeen.';
        $_SESSION['feedback_color'] = 'red';
    }
    header('Location: gebruiker.php');
    exit(0);
}

if (isset($_GET['annuleerBestelling']) && $_GET['annuleerBestelling'] !== '') {
    $bestellingSvc->annuleerBestelling(intval($_GET['annuleerBestelling']));
    $_SESSION['feedback'] = 'Bestelling succesvol geannuleerd!';
    $_SESSION['feedback_color'] = 'green';
    header('Location: gebruiker.php');
    exit(0);
}
//Voor het verwijderen van een review
if (isset($_GET['deletereview']) && $_GET['deletereview'] !== '') {
    $artikelSvc->deleteReview(intval($_GET['deletereview']));
    $_SESSION['feedback'] = 'Review verwijderd!';
    $_SESSION['feedback_color'] = 'green';
    header('Location: gebruiker.php');
    exit(0);
}

if (isset($_GET['bewerkreview']) && $_GET['bewerkreview'] !== '') {

    //Controleer dat de gegeven rating zeker tussen 0 en 5 ligt, en dat de comment niet langer is dan 255 characters
    if (intval($_POST['ster_input']) >= 0 && intval($_POST['ster_input']) <= 5 && strlen($_POST['newComment']) <= 255) {
        $artikelSvc->modifyReview(intval($_GET['bewerkreview']), intval($_POST['ster_input']), $_POST['newComment']);
        $_SESSION['feedback'] = 'Review bewerkt!';
        $_SESSION['feedback_color'] = 'green';
        header('Location: gebruiker.php');
        exit(0);
    } else {
        $_SESSION['feedback'] = 'Je review bevat ongeldige informatie';
        $_SESSION['feedback_color'] = 'red';
        header('Location: gebruiker.php');
        exit(0);
    }

}

if (isset($_GET['blokkeeruser']) && $_GET['blokkeeruser'] !== '') {
    //blokkeer de user
    $gebruikerSvc->blockGebruiker(intval($_GET['blokkeeruser']));

    //verwijder de cookie
    setcookie("gebruiker", "", time() - 3600);

    //redirect naar het overzicht
    header('Location: overzicht.php');
    exit(0);
}

if (isset($_GET['annuleerartikel']) && $_GET['annuleerartikel'] !== '') {
    $bestellingSvc->annuleerLosseStuks(intval($_GET['annuleerartikel']), intval($_POST['annuleer-stuks']));
    $_SESSION['feedback'] = 'Artikel(s) geannuleerd!';
    $_SESSION['feedback_color'] = 'green';
    header('Location: gebruiker.php');
    exit(0);
}


include("presentation/gebruikerPresentation.php");

//toon feedback aan de user
if (isset($_SESSION['feedback'])) {
    $feedback = new Feedback($_SESSION['feedback'], $_SESSION['feedback_color']);
    echo $feedback->getFeedback();
    unset($_SESSION['feedback']);
    unset($_SESSION['feedback_color']);
}
?>