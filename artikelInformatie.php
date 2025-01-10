<?php

declare(strict_types=1);
require_once('data/autoloader.php');

$artikelService = new ArtikelService();
$gebruikerService = new GebruikerService();
$bestellingService = new BestellingService();

$artikelSpecifiek = $artikelService->getArtikelById(intval($_GET["id"]));
$reviewToegelaten = false;
$arr_alleCategorieen =  $artikelService->getCategorieen();
//$arr_hoofdcategorieen = $arr_alleCategorieen->getCat ;
//$arr_subcategorieen = ;



/* Gemiddele berekenen van reviewscores + berekening niet doen als aantal reviews gelijk is aan 0*/

$artikelSpecifiekReviews = $artikelService->getReviewByArtikelId(intval($_GET['id']));

$aantalReviews = count($artikelSpecifiekReviews);
$gemiddeldeScore = 0;
if ($aantalReviews > 0) {
    $somScore = 0;
    foreach ($artikelSpecifiekReviews as $review) {
        $score = $review->getScore();
        $somScore += $score;
    }
    
    $gemiddeldeScore = round($somScore / $aantalReviews, 2);
}


$thumbnailPath = 'assets/thumbnailArtikels/artikelVoorbeeldImg.png';
if (file_exists('assets/thumbnailArtikels/' . $artikelSpecifiek->getId() . '.png')) {
    $thumbnailPath = 'assets/thumbnailArtikels/' . $artikelSpecifiek->getId() . '.png';
}



//Controleer als de gebruiker een review kan plaatsen, dit kan enkel als de gebruiker ingelogd is, en dit artikel in zijn bestelgeschiedenis staat
if (isset($_COOKIE["gebruiker"])) {
    $gebruiker = unserialize($_COOKIE['gebruiker']);

    //In deze array staan alle bestellingen van de ingelogde klant
    $arr_bestellingenKlant = $bestellingService->getBestellingenByKlantId(intval($gebruiker->getKlantId()));

    //In deze array komen alle bestellijnen te staan van deze klant
    $arr_bestellijnenKlant = array();

    //Voor elke bestelling van bestellingen van de klant
    foreach ($arr_bestellingenKlant as $bestelling) {
        //Voor elke bestellijn van elke bestelling van de klant
        foreach ($bestelling->getBestellijnen() as $bestellijn) {
            array_push($arr_bestellijnenKlant, $bestellijn);
        }
    }

    //Declaratie van het bestellijnId voor een review te kunnen wegschrijven
    $bestellijnId = 0;

    //Controleer als de klant al eens een bestelling heeft geplaatst voor dit artikel, zo ja, kan hij er geen meer plaatsen
    if ($artikelService->heeftKlantAlEenReviewGeplaatstVanDitArtikel(intval($gebruiker->getKlantId()), $artikelSpecifiek->getId())) {
        //controleer als het artikelId in de bestellijnen van de klant zit, en zo ja, sla dit bestellijnId op in een globale variable
        foreach ($arr_bestellijnenKlant as $bestellijn) {
            if ($artikelSpecifiek->getId() == $bestellijn->getArtikelId()) {
                $reviewToegelaten = true;
                $bestellijnId = $bestellijn->getBestellijnId();
            }
        }
    }



    //Als de gebruiker een review plaatst
    if (isset($_GET['action']) && $_GET['action'] == 'plaatsReview') {
        $auteur = $gebruiker->getVoornaam();
        $score = intval($_POST['rating']);
        $commentaar = $_POST['commentaar'];
        $datum = date("Y/m/d H:i:s");

        $artikelService->plaatsReview($auteur, $score, $commentaar, $datum, $bestellijnId);

        header('Location: artikelInformatie.php?id=' . $artikelSpecifiek->getId());
        exit(0);
    }
}



include("presentation/productInfo.php");
