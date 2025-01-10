<?php
// ./contact.php
declare(strict_types=1);
require_once('data/autoloader.php');
session_start();


$standaardVoornaam = '';
$standaardFamilienaam = '';
$standaardEmail = '';

// Haal gebruikersinformatie uit de cookie als deze bestaat
if (isset($_COOKIE['gebruiker'])) {
    $gebruiker = unserialize($_COOKIE['gebruiker']);

    // Controleer of de gebruikersinformatie en e-mail aanwezig zijn in de cookie
    if ($gebruiker && $gebruiker->getEmailadres()) {
        $standaardVoornaam = $gebruiker->getVoornaam();
        $standaardFamilienaam = $gebruiker->getFamilienaam();
        $standaardEmail = $gebruiker->getEmailadres();
    }
}

// Controleer of het formulier is ingediend
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Plaats hier de logica om het formulier te verwerken en het bericht te verzenden

    $_SESSION['success_message'] = "Uw bericht is succesvol verstuurd!";

    // Redirect naar dezelfde pagina om de refresh van de browser te hanteren
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

if (isset($_SESSION['success_message'])) {
    echo '<div class="success-message">' . $_SESSION['success_message'] . '</div>';

    // Wis de succesmelding om te voorkomen dat deze opnieuw wordt getoond na een refresh
    unset($_SESSION['success_message']);
}

include('presentation/contact.php');