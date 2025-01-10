<?php
//inloggen.php
declare(strict_types=1);
require_once('data/autoloader.php');
session_start();

$gebruikerService = new GebruikerService();
$adresService = new AdresService();

// Als action logout is ================================================

if (isset($_GET["action"]) && $_GET["action"] === "logout" && isset($_COOKIE['gebruiker'])) {
    setcookie('gebruiker', '');
    header('Location: inloggen.php');
    exit(0);
}

// Als action login is ================================================

if (isset($_GET["action"]) && $_GET["action"] === "login") {
    $toegelaten = $gebruikerService->validateGebruiker((string) strtolower($_POST['log_email']), (string) $_POST["log_paswoord"]);

    if ($toegelaten) {

        // Volledige gebruiker serializen in cookiee!!!!!!!!!!!!!!!!!!!!!!!!!!

        $gebruiker = $gebruikerService->getGebruikerByEmailadres($_POST['log_email']);

        setcookie('gebruiker', serialize($gebruiker));
        header('Location: overzicht.php');
        exit(0);
    } else {
        $_SESSION['feedback'] = 'We kunnen je niet inloggen met deze gegevens. Controleer de gegevens of neem contact op met administratie.';
        $_SESSION['feedback_color'] = 'red';
        header('Location: inloggen.php');
        exit(0);
    }
}

// Als action register is ================================================

if (isset($_GET["action"]) && $_GET["action"] === "register") {

    //Basis gegevens

    $emailadres = strtolower($_POST['reg_email']);
    $paswoord = $_POST['reg_paswoord'];
    $paswoord2 = $_POST['reg_paswoord2'];
    $voornaam = $_POST['reg_voornaam'];
    $familienaam = $_POST['reg_familienaam'];

    //Leverings adres

    $straat = $_POST['reg_straat'];
    $huisNummer = $_POST['reg_huisNummer'];
    $bus = $_POST['reg_bus'];
    $plaats = $_POST['reg_plaats'];
    $postcode = $_POST['reg_postcode'];

    //Facturatie adres

    if (isset($_POST['checkFacturatie'])) {
        $checkFacturatie = true;
    } else {
        $checkFacturatie = false;
    }

    if (!$checkFacturatie) {
        $facturatieStraat = $_POST['reg_facturatieStraat'];
        $facturatieHuisNummer = $_POST['reg_facturatieHuisNummer'];
        $facturatieBus = $_POST['reg_facturatieBus'];
        $facturatiePlaats = $_POST['reg_facturatiePlaats'];
        $facturatiePostcode = $_POST['reg_facturatiePostcode'];

        setcookie('facturatieStraat', $facturatieStraat, time() + 3600, '/');
        setcookie('facturatieHuisNummer', $facturatieHuisNummer, time() + 3600, '/');
        setcookie('facturatieBus', $facturatieBus, time() + 3600, '/');
        setcookie('facturatiePlaats', $facturatiePlaats, time() + 3600, '/');
        setcookie('facturatiePostcode', $facturatiePostcode, time() + 3600, '/');
    }

    setcookie('emailadres', $emailadres, time() + 3600, '/');
    setcookie('voornaam', $voornaam, time() + 3600, '/');
    setcookie('familienaam', $familienaam, time() + 3600, '/');

    setcookie('straat', $straat, time() + 3600, '/');
    setcookie('huisNummer', $huisNummer, time() + 3600, '/');
    setcookie('bus', $bus, time() + 3600, '/');
    setcookie('plaats', $plaats, time() + 3600, '/');
    setcookie('postcode', $postcode, time() + 3600, '/');

    if ($gebruikerService->validatePaswoordRepetition($paswoord, $paswoord2)) {
        $emailValidationResult = $gebruikerService->validateEmailadres($emailadres);
        if ($emailValidationResult === true) {
            if ($adresService->controleerPostcode($postcode)) {

                if ($checkFacturatie) {
                    $gebruikerService->addGebruiker(
                        $emailadres,
                        $paswoord,
                        $voornaam,
                        $familienaam,
                        $straat,
                        $huisNummer,
                        $bus,
                        $plaats,
                        $postcode,
                        $straat,
                        $huisNummer,
                        $bus,
                        $plaats,
                        $postcode,
                        $checkFacturatie
                    );
                } else {
                    if ($adresService->controleerPostcode($facturatiePostcode)) {
                        $gebruikerService->addGebruiker(
                            $emailadres,
                            $paswoord,
                            $voornaam,
                            $familienaam,
                            $straat,
                            $huisNummer,
                            $bus,
                            $plaats,
                            $postcode,
                            $facturatieStraat,
                            $facturatieHuisNummer,
                            $facturatieBus,
                            $facturatiePlaats,
                            $facturatiePostcode,
                            $checkFacturatie
                        );
                    } else {
                        setcookie('facturatiePostcode', '');
                        $_SESSION['feedback'] = 'De postcode van het facturatieadres is ongeldig.';
                        $_SESSION['feedback_color'] = 'red';
                    }
                }

                // Indien correct geregistreerd -------------------------------

                // Cookies unsetten

                // setcookie('emailadres', '');
                // setcookie('voornaam', '');
                // setcookie('familienaam', '');

                // setcookie('straat', '');
                // setcookie('huisNummer', '');
                // setcookie('bus', '');
                // setcookie('plaats', '');
                // setcookie('postcode', '');

                // setcookie('facturatieStraat', '');
                // setcookie('facturatieHuisNummer', '');
                // setcookie('facturatieBus', '');
                // setcookie('facturatiePlaats', '');
                // setcookie('facturatiePostcode', '');

                $_SESSION['feedback'] = 'Account aangemaakt! U kan nu inloggen.';
                $_SESSION['feedback_color'] = 'green';

                // Indien correct geregistreerd ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

            } else {
                setcookie('postcode', '');
                $_SESSION['feedback'] = 'De postcode van het leveringsadres is ongeldig.';
                $_SESSION['feedback_color'] = 'red';
            }
        } elseif ($emailValidationResult === 'invalid_format') {
            $_SESSION['feedback'] = 'Emailadres heeft een ongeldig formaat.';
            $_SESSION['feedback_color'] = 'red';
        } else {

            setcookie('emailadres', '');
            $_SESSION['feedback'] = 'Dit account bestaat al.';
            $_SESSION['feedback_color'] = 'red';
        }
    } else {
        $_SESSION['feedback'] = 'De gegeven wachtwoorden komen niet overeen.';
        $_SESSION['feedback_color'] = 'red';
    }
    header('Location: inloggen.php');
    exit(0);
}
include('presentation/Inlogformulier.php');

if (isset($_SESSION['feedback'])) {
    $feedback = new Feedback($_SESSION['feedback'], $_SESSION['feedback_color']);
    echo $feedback->getFeedback();
    unset($_SESSION['feedback']);
    unset($_SESSION['feedback_color']);
}
