<?php
declare(strict_types=1);
require_once('data/autoloader.php');
class BestellingService
{
    public function addBestelBon(int $klantId, int $betaald, int $betaalMethode, int $bestellingStatusId, int $actiecodeGebruikt, string $bedrijfsnaam, string $btwnummer, string $voornaam, string $familienaam, int $factuurAdresId, int $leverAdresId): int
    {
        $bestellingDAO = new BestellingDAO();
        $tijdstip = date("Y/m/d H:i:s");
        $tijdstipFormatted = str_replace(':', '', $tijdstip);
        $tijdstipFormatted = str_replace(' ', '', $tijdstip);
        $tijdstipFormatted = str_replace(' -', '', $tijdstip);
        $betalingsCode = 'K' . $tijdstipFormatted;

        $empty_array = array();

        $bestelling = new Bestelling(999, $tijdstip, $klantId, $betaald, $betalingsCode, $betaalMethode, 0, null, '', $bestellingStatusId, $actiecodeGebruikt, $bedrijfsnaam, $btwnummer, $voornaam, $familienaam, $factuurAdresId, $leverAdresId, $empty_array);

        $bestelbonId = $bestellingDAO->addBestelBon($bestelling);
        return $bestelbonId;
    }

    public function addBestelLijn($bestelId, $artikelId, $aantalBesteld)
    {
        $bestellingDAO = new BestellingDAO();
        $bestellingDAO->addBestelLijn($bestelId, $artikelId, $aantalBesteld, 0);
    }

    public function getBestellingenByKlantId(int $klantId)
    {
        $bestellingDAO = new BestellingDAO();
        return $bestellingDAO->getBestellingenByKlantId($klantId);
    }

    public function getBetaalwijzeById(int $betaalwijzeId): string
    {
        $bestellingDAO = new BestellingDAO();
        return $bestellingDAO->getBetaalwijzeById($betaalwijzeId);
    }

    public function getBestellingsStatusById(int $bestellingsStatusId): string
    {
        $bestellingDAO = new BestellingDAO();
        return $bestellingDAO->getBestellingsStatusById($bestellingsStatusId);
    }

    public function annuleerBestelling(int $bestelid)
    {
        $bestellingDAO = new BestellingDAO();
        $bestellingDAO->annuleerBestelling($bestelid);
    }

    public function annuleerLosseStuks(int $bestellijnId, int $aantal)
    {
        $bestellingDAO = new BestellingDAO();
        $bestellingDAO->annuleerLosseStuks($bestellijnId, $aantal);
    }

}