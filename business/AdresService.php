<?php
declare(strict_types=1);
require_once('data/autoloader.php');
class AdresService {
    public function getAdressen(): array {
        $adresDAO = new AdresDAO();
        $lijst = $adresDAO->getAll();
        return $lijst;
    }

    public function getAdresById(int $adresid): ?Adres {
        $lijst_adressen = $this->getAdressen();
        foreach($lijst_adressen as $adres) {
            if($adres->getAdresById() === $adresid) {
                return $adres;
            }
        }
    }

    public function controleerAlsAdresBestaat(string $straat, string $huisnummer, string $bus, string $postcode, string $plaats) : ?int
    {
        $adresDAO = new AdresDAO();
        return $adresDAO->controleerAdres($straat, $huisnummer, $bus, $postcode, $plaats);
    }

    public function addAdres(string $straat, string $huisNummer, string $bus, string $plaats, string $postcode) : bool
    {
        $adresDAO = new AdresDAO();
        $geldigePostcode = $adresDAO->addAdres($straat, $huisNummer, $bus, $plaats, $postcode);
        return $geldigePostcode;
    }

    public function controleerPostcode(string $postcode): bool
    {
        $adresDAO = new AdresDAO();
        $postcodeBestaat = $adresDAO->controleerPostcode($postcode);

        return $postcodeBestaat;
    }
    
}