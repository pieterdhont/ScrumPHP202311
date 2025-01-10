<?php

declare(strict_types=1);
require_once('data/autoloader.php');

if (isset($_COOKIE['gebruiker'])) {
    $gebruiker = unserialize($_COOKIE['gebruiker']);
}

// Nodige Variablen voor de overzicht pagina
$artikelService = new ArtikelService();
$artikels = []; // Initialiseer $artikels als een lege array
$spotlightArtikels = []; // Initialiseer $spotlightArtikels als een lege array

// Sorteerfunctie Variabelen
$sorteerOp = isset($_GET['sorteerOp']) ? $_GET['sorteerOp'] : null;
$sorteerRichting = isset($_GET['sorteerRichting']) ? $_GET['sorteerRichting'] : null;

// Zoekfunctie Variablen
$zoekterm = $_GET['zoekterm'] ?? '';
$validatieFouten = '';
$geenResultaten = false;

// Categorieen variabele
$categorieen = $artikelService->getCategorieen(); //  Gebruikt om menu aan te maken
$categorieId = isset($_GET['categorie']) ? (int) $_GET['categorie'] : null;
$hoofdCategorieId = isset($_GET['hoofdcategorie']) ? (int) $_GET['hoofdcategorie'] : null;
$subCategorieId = isset($_GET['subcategorie']) ? (int) $_GET['subcategorie'] : null;

// paginaNummer ophalen
if (isset($_GET['pagina'])) {
    $paginaNummer = (int) $_GET['pagina'];
} else {
    $paginaNummer = 1;
}

$maxAantalArtikelsPerPagina = 24;

// START $artikels instellen volgens sorteer of zoekmethode =====================

// Validatie voor zoekterm
if (!empty($zoekterm) && (strlen($zoekterm) < 3)) {
    $validatieFouten = "De zoekterm moet minstens 3 tekens lang zijn.";
    $artikels = [];
    $geenResultaten = true;
} else {
    // Gebruik van de nieuwe getArtikelen functie
    $zoekterm = trim($zoekterm);
    $artikels = $artikelService->getArtikelenSpecifiek($zoekterm, $categorieId, $hoofdCategorieId, $subCategorieId, $sorteerOp, $sorteerRichting, $paginaNummer, $maxAantalArtikelsPerPagina);
    if (empty($artikels)) {
        $geenResultaten = true;
    }
}

if (!empty($artikels)) {
    $aantalArtikels = $artikelService->getAantalArtikels($zoekterm, $categorieId, $subCategorieId, $hoofdCategorieId);
    $aantalPaginas = ceil($aantalArtikels / $maxAantalArtikelsPerPagina);
} else {
    $aantalPaginas = 1;
}

function toonArtikel($artikel)
{
    $artikelService = new ArtikelService();

    $score = $artikelService->getGemiddeldeScorePerArtikel($artikel->getId());
    $thumbnailPath = 'assets/thumbnailArtikels/artikelVoorbeeldImg.png';
    if (file_exists('assets/thumbnailArtikels/' . $artikel->getId() . '.png')) {
        $thumbnailPath = 'assets/thumbnailArtikels/' . $artikel->getId() . '.png';
    } ?>
    <div class="artikelContainer" id="artikel<?php echo $artikel->getId(); ?>" data-id="<?php echo $artikel->getId(); ?>">
        <div class="fotoContainer">
            <img src="<?php echo $thumbnailPath; ?>" loading="lazy" alt="<?php echo $artikel->getNaam(); ?> foto" title="<?php echo $artikel->getNaam(); ?>">
        </div>
        <div class="artikelinfo">
            <p class="artikelNaam"><?php echo $artikel->getNaam(); ?></p>
            <p class="artikelPrijs">&euro;<?php echo number_format((float) $artikel->getPrijs(), 2, '.', ''); ?></p>
            <!---------Nog toegevoegd door Greg, om de voorraad te bepalen-------------->
            <?php
            if ($artikel->getVoorraad() == 0) { ?>
                <p class="artikelVoorraad">Niet meer in voorraad</p>
            <?php } else if ($artikel->getVoorraad() < 5) { ?>
                <p class="artikelVoorraad">Nog <?php echo $artikel->getVoorraad(); ?> stuk(s) beschikbaar!</p>
            <?php } ?>
            <!--------------------------------EINDE GREG--------------------------------->
            <?php if (is_null($score)) { ?>
                <div class="score geen">Geen reviews</div>
            <?php } else { ?>
                <div class="score" style="--percent: <?php print($score / 5 * 100); ?>%;" title="<?php echo $score ?> van 5 steren">★★★★★</div>
            <?php } ?>

            <?php if ($artikel->getVoorraad() > 0) { ?>
                <a href="#" class="toevoegenAanWinkelmandKnop" data-voorraad="<?php echo $artikel->getVoorraad(); ?>" data-id="<?php echo $artikel->getId(); ?>" data-prijs="<?php echo $artikel->getPrijs(); ?>" data-naam="<?php echo $artikel->getNaam(); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-cart-plus" viewBox="0 0 16 16">
                        <path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z" />
                        <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7h-8.17zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                    </svg>
                </a>
            <?php } ?>

        </div>
        <button onclick="window.location.href = 'artikelInformatie.php?id=<?php echo $artikel->getId(); ?>';">Meer info</button>
    </div>
<?php
}

// Artikels ophalen code voor de spotlight
if (isset($gebruiker)) {
    $gebruikerService = new GebruikerService();
    $intersanteCategorie = $gebruikerService->getDeFavorieteCategorieVanEenKlant($gebruiker->getKlantId());
    if ($intersanteCategorie != null) {
        $spotlightArtikels = $artikelService->getArtikelenbyCategorie($intersanteCategorie['categorieId']);
        if (count($spotlightArtikels) > 10) {
            array_splice($spotlightArtikels, 10);
        } else if (count($spotlightArtikels) < 10) {
            $intersanteHoofdcategorieId = $artikelService->getDeHoofdcategorieVanSubcategorie($intersanteCategorie['categorieId']);
            $hoofdcategorieArtikels = $artikelService->getArtikelenbyHoofdCategorie($intersanteHoofdcategorieId);
            $i = 0;
            while (count($spotlightArtikels) < 10) {
                $artikelBesaatAl = false;
                foreach ($spotlightArtikels as $artikel) {
                    if ($hoofdcategorieArtikels[$i]->getId() == $artikel->getId()) {
                        $artikelBesaatAl = true;
                        break;
                    }
                }

                if (!$artikelBesaatAl) {
                    array_push($spotlightArtikels, $hoofdcategorieArtikels[$i]);
                }

                $i++;
            }
        }
    } else {
        $spotlightArtikels = $artikelService->getDeMeesteVerkochteArtikels();
    }
} else {
    $spotlightArtikels = $artikelService->getDeMeesteVerkochteArtikels();
}
// End spotlight code

// Get categorienaam van de variabele $categorieen

function getCategorieNaam($categorieenArray, $categorieId)
{
    foreach ($categorieenArray as $hoofdId => $hoofdCategorie) {
        if ($hoofdId == $categorieId) {
            return $hoofdCategorie['naam'];
        }

        foreach ($hoofdCategorie['subcategorieen'] as $subId => $subCategorie) {
            if ($subId == $categorieId) {
                return $subCategorie['naam'];
            }

            foreach ($subCategorie['subSubcategorieen'] as $subSubCategorie) {
                if ($subSubCategorie['categorieId'] == $categorieId) {
                    return $subSubCategorie['naam'];
                }
            }
        }
    }

    return null; // Geeft null terug als er geen overeenkomende naam gevonden is voor de gegeven categorieId

}



include("presentation/overzichtPresentation.php");
