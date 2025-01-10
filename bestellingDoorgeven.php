<?php
declare(strict_types=1);
require_once('data/autoloader.php');
session_start();

$actiecodeFeedback = '';
$actiecodeFout = '';

// Als de user geen bestelling geselecteerd heeft, terugverwijzen naar overzicht.php
if (!isset($_COOKIE['order']) || count(json_decode($_COOKIE['order'])) < 1) {
    header('Location: overzicht.php');
    exit(0);
}

// Als de user niet ingelogd is, verwijs naar de inlogpagina
/*Hier zou later nog de functionaliteit bijkomen dat de user wel een bestelling kan plaatsen als gast,
Maar door technische reden is dit uitgesteld*/
if (!isset($_COOKIE['gebruiker']) || $_COOKIE['gebruiker'] == '') {
    header('Location: inloggen.php');
    exit(0);
}

//deze code word enkel uitgevoerd als de user is ingelogd en er een order in de cookie steekt

//Haal de cookie 'order' op en steek deze in een array
$orderCookie = json_decode($_COOKIE['order']);

//Haal het gebruiker object uit de cookie gebruiker
$gebruiker = unserialize($_COOKIE['gebruiker']);

//declareer de nodige services
$artikelSvc = new ArtikelService();
$gebruikerSvc = new GebruikerService();
$actiecodeSvc = new ActiecodeService();
$adresSvc = new AdresService();
$bestellingSvc = new BestellingService();


//voer deze code uit als de gebruiker zijn bestelling bevestigd
$actiecode = '';
if (isset($_GET['action']) && $_GET['action'] == 'bevestig') {
    //Haal alle gegevens op
    $voornaam = $_POST['klant_voornaam'];
    $familienaam = $_POST['klant_familienaam'];
    $betaalMethode = intval($_POST['betaalmethode']);
    $bedrijfsnaam = $_POST['klant_bedrijfsnaam'];
    $btwnummer = $_POST['klant_BTWNummer'];

    $factuur_straat = $_POST['factuur_straat'];
    $factuur_huisnummer = $_POST['factuur_huisnummer'];
    $factuur_bus = $_POST['factuur_bus'];
    $factuur_postcode = $_POST['factuur_postcode'];
    $factuur_plaats = $_POST['factuur_plaats'];

    $lever_straat = $_POST['lever_straat'];
    $lever_huisnummer = $_POST['lever_huisnummer'];
    $lever_bus = $_POST['lever_bus'];
    $lever_postcode = $_POST['lever_postcode'];
    $lever_plaats = $_POST['lever_plaats'];

    $actiecodeNaam = $_POST['actiecode'];

    //Verwijder actiecode uit de database als deze eenmalig is
    if ($actiecodeNaam !== '') {
        $actiecode = $actiecodeSvc->getActieCode($actiecodeNaam);
        if ($actiecode->getActiecodeIsEenmalig() == 1) {
            //Als de actiecode IsEenamlig 1 is, verwijder deze uit de database
            $actiecodeSvc->verwijderActiecode($actiecode);
        }
    }

    //Als het leveradres hetzelfde is als het facturatieadres, overschrijf de leveradres gegevens met de facturatieadresgegevens
    if (isset($_POST['gelijkadres'])) {
        $lever_straat = $factuur_straat;
        $lever_huisnummer = $factuur_huisnummer;
        $lever_bus = $factuur_bus;
        $lever_postcode = $factuur_postcode;
        $lever_plaats = $factuur_plaats;
    }

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

    //Kijk als het leveradres al bestaat in de database
    $leverAdresId = $adresSvc->controleerAlsAdresBestaat($lever_straat, $lever_huisnummer, $lever_bus, $lever_postcode, $lever_plaats);

    //Als het leveradres niet bestaat (0 is returned), voeg het toe aan de database
    if ($leverAdresId == 0) {
        if ($adresSvc->addAdres($lever_straat, $lever_huisnummer, $lever_bus, $lever_postcode, $lever_plaats) == false) {
            $geldigePostcode = false;
        }
        $leverAdresId = $adresSvc->controleerAlsAdresBestaat($lever_straat, $lever_huisnummer, $lever_bus, $lever_postcode, $lever_plaats);
    }

    $klantId = $gebruiker->getKlantId();

    $betaald = 0;
    if ($betaalMethode == 1) {
        $betaald = 1;
    }


    $bestellingStatusId = 1;
    if ($betaald = 1) {
        $bestellingStatusId = 2;
    }

    $actiecodeGebruikt = 0;
    if (isset($_POST['actiecode']) && $_POST['actiecode'] !== '') {
        $actiecodeGebruikt = 1;
    }


    //Voeg een bestelbon toe aan de database en krijg het ID van de toegevoegde bestelbon terug
    if ($geldigePostcode) {
        $bestelId = $bestellingSvc->addBestelBon($klantId, $betaald, $betaalMethode, $bestellingStatusId, $actiecodeGebruikt, $bedrijfsnaam, $btwnummer, $voornaam, $familienaam, $factuurAdresId, $leverAdresId);
    } else {
        $_SESSION['feedback'] = 'Dit is geen geldige postcode.';
        $_SESSION['feedback_color'] = 'red';
        header('Location: bestellingDoorgeven.php');
        exit(0);
    }


    //VOOR ELKE BESTELLIJN IN DE BESTELLING, VOEG HET BESTELLIJN TOE AAN BESTELLIJNEN SAMEN MET HET BESTELID VAN DE BON
    //DIT KAN OP DE VOLGENDE MANIER

    foreach ($orderCookie as $orderLine) {
        $bestellingSvc->addBestelLijn($bestelId, intval($orderLine->id), intval($orderLine->aantal));
    }

    //DE USER REDIRECTEN NAAR EEN BESTELLINGGEPLAATST PAGINA, VOORLOPIG TERUG HET OVERZICHT
    //verwijder de cookie van het winkelmandje als de bestelling is afgerond
    setcookie("order", "", time() - 3600);
    header('Location: bestellingFeedback.php');
    exit(0);
}

/*Controleer als de actiecode bestaat*/
$korting = 0;
if (isset($_POST['actiecode']) && $_POST['actiecode'] !== '') {

    $actiecode_object = $actiecodeSvc->getActieCode($_POST['actiecode']);

    if ($actiecode_object) {
        if ($actiecodeSvc->valideerActieCode($actiecode_object)) {
            $actiecodeFeedback = 'Deze actiecode is geldig.';
            $korting = 10;
            $actiecode = $_POST['actiecode'];
        } else {
            $actiecodeFout = 'Deze actiecode is vervallen';
        }
    } else {
        $actiecodeFout = 'Deze actiecode bestaat niet.';
        unset($_POST['actiecode']);
    }
}


/*Totale bestelbedrag berekenen*/
$totaalBedrag = 0;
foreach ($orderCookie as $orderLine) {
    $totaalBedrag += $orderLine->prijs * $orderLine->aantal;
}

//pas eventuele korting toe
$teBetalen = $totaalBedrag * ((100 - $korting) / 100);

if ($korting !== 0) {
    $totaalClass = "afrekenenTekst metKorting";
} else {
    $totaalClass = "afrekenenTekst";
}


include('presentation/bestelpagina.php');

//toon feedback aan de user
if (isset($_SESSION['feedback'])) {
    $feedback = new Feedback($_SESSION['feedback'], $_SESSION['feedback_color']);
    echo $feedback->getFeedback();
    unset($_SESSION['feedback']);
    unset($_SESSION['feedback_color']);
}







