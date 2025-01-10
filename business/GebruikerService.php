<?php
//GebruikerService.php
declare(strict_types=1);
require_once('data/autoloader.php');
class GebruikerService
{
    public function getGebruikers(): array
    {
        $gebruikerDAO = new GebruikerDAO();
        $lijst = $gebruikerDAO->getAll();
        return $lijst;
    }

    public function getGebruikerByAccountId(int $id): ?Gebruiker
    {
        $lijst_gebruikers = $this->getGebruikers();
        foreach ($lijst_gebruikers as $gebruiker) {
            if ($gebruiker->getGebruikersAccountId() === $id) {
                return $gebruiker;
            }
        }
    }

    public function getGebruikerByKlantId(int $klantId): ?Gebruiker
    {
        $lijst_gebruikers = $this->getGebruikers();
        foreach ($lijst_gebruikers as $gebruiker) {
            if ($gebruiker->getKlantId() === $klantId) {
                return $gebruiker;
            }
        }
        return null;
    }

    public function getGebruikerByEmailadres(string $emailadres): ?Gebruiker
    {
        $lijst_gebruikers = $this->getGebruikers();

        foreach ($lijst_gebruikers as $gebruiker) {
            if ($gebruiker->getEmailadres() === strtolower($emailadres)) {
                return $gebruiker;
            }
        }

        return null;
    }

    public function addGebruiker(
        string $emailadres,
        string $paswoord,
        string $voornaam,
        string $familienaam,
        string $straat,
        string $huisNummer,
        string $bus,
        string $plaats,
        string $postcode,
        string $facturatieStraat,
        string $facturatieHuisNummer,
        string $facturatieBus,
        string $facturatiePlaats,
        string $facturatiePostcode,
        bool $checkFacturatie
    ) {
        $gebruikerDAO = new GebruikerDAO();
        $gebruikerDAO->addGebruiker(
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
    }

    public function validateGebruiker(string $gebruiker_emailadres, string $gebruiker_paswoord): ?Gebruiker
    {
        $gebruikerDAO = new GebruikerDAO();
        $lijst_gebruikers = $gebruikerDAO->getAll();

        foreach ($lijst_gebruikers as $gebruiker) {
            if ($gebruiker->getEmailadres() == $gebruiker_emailadres && password_verify($gebruiker_paswoord, $gebruiker->getPaswoord())) {  
                return $gebruiker;
            }
        }

        return null;
    }

    public function validatePaswoordRepetition(string $paswoord, string $paswoord2): bool
    {
        if ($paswoord === $paswoord2) {
            return true;
        } else {
            return false;
        }
    }

    public function validateEmailadres(string $emailadres) {
        if (!filter_var($emailadres, FILTER_VALIDATE_EMAIL)) {
            return 'invalid_format';
        }
        $lijst_gebruikers = $this->getGebruikers();
        foreach ($lijst_gebruikers as $gebruiker) {
            if ($gebruiker->getEmailadres() === $emailadres) {
                return 'exists';
            }
        }
        return true;
    }

    public function wijzigWachtwoord(int $gebruikersAccountId, string $nieuw_wachtwoord)
    {
        $gebruikerDAO = new GebruikerDAO();
        $gebruikerDAO->wijzigWachtwoord($gebruikersAccountId, $nieuw_wachtwoord);
    }

    public function wijzigFactuurAdres(int $klantId, int $facturatieAdresId)
    {
        $gebruikerDAO = new GebruikerDAO();
        $gebruikerDAO->wijzigFactuurAdres($klantId, $facturatieAdresId);
    }

    public function wijzigLeverAdres(int $klantId, int $leverAdresId)
    {
        $gebruikerDAO = new GebruikerDAO();
        $gebruikerDAO->wijzigLeverAdres($klantId, $leverAdresId);
    }

    public function wijzigBedrijfsGegevens(int $klantId, string $bedrijfsnaam, string $btwnummer)
    {
        $gebruikerDAO = new GebruikerDAO();
        $gebruikerDAO->wijzigBedrijfsGegevens($klantId, $bedrijfsnaam, $btwnummer);
    }

    public function getReviewsByKlantId(int $klantId) : array
    {
        $reviewDAO = new ReviewDAO();
        return $reviewDAO->getReviewsByKlantId($klantId);
    }

    public function getDeFavorieteCategorieVanEenKlant(int $klantId): ?array {
        $gebruikerDAO = new GebruikerDAO();
        $favorieteCategorie = $gebruikerDAO->getDeFavorieteCategorieVanEenKlant($klantId);
        
        return $favorieteCategorie;
    }

    public function getDeGekochteCategorieenVoorEenGebruiker(int $klantId): ?array {
        $bestellingDAO = new BestellingDAO();
        $artikelDAO = new ArtikelDAO();
        $bestellingen = $bestellingDAO->getBestellingenByKlantId($klantId);
        if (count($bestellingen) > 0) {
            $gekochteArtikels = array();
            $gekochteCategorieen = array();
            foreach ($bestellingen as $bestelling) {
                $bestellijnen = $bestelling->getBestellijnen();
                foreach ($bestellijnen as $bestellijn) {
                    $artikel = $artikelDAO->getArtikelById($bestellijn->getArtikelId());
                    if (($bestellijn->getAantalBesteld() - $bestellijn->getAantalGeannuleerd()) > 0)
                        array_push($gekochteArtikels, $artikel);
                }
            }

            foreach ($gekochteArtikels as $artikel) {
                $artikelCategorie = $artikel->getCategorieId();
                $categorieIsNew = true;
                for ($i = 0; $i < count($gekochteCategorieen); $i++) {
                    if ($gekochteCategorieen[$i]["categorieId"] == $artikelCategorie) {
                        $gekochteCategorieen[$i]["aantal"]++;
                        $categorieIsNew = false;
                        break;
                    }
                }
                if ($categorieIsNew) {
                    $gekochteCategorie = array("categorieId" => $artikelCategorie, "aantal" => 1);
                    array_push($gekochteCategorieen, $gekochteCategorie);
                }    
            }

            return $gekochteCategorieen;
        } else return null;
    }

    public function blockGebruiker(int $gebruikersAccountId)
    {
        $gebruikerDAO = new GebruikerDAO();
        $gebruikerDAO->blockGebruiker($gebruikersAccountId);
    }
    
}
