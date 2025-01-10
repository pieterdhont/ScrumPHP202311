<?php
declare(strict_types=1);

class Bestelling
{
    private int $bestelId;
    private $besteldatum;
    private int $klantId;
    private int $betaald;
    private string $betalingscode;
    private int $betaalwijzeId;
    private int $annulatie;
    private $annulatiedatum;
    private string $terugbetalingsCode;
    private int $bestellingsStatusId;
    private int $actiecodeGebruikt;
    private string $bedrijfsnaam;
    private string $btwNummer;
    private string $voornaam;
    private string $familienaam;
    private int $facturatieAdresId;
    private int $leveringsAdresId;
    private array $bestellijnen;

    public function __construct($bestelId, $besteldatum, $klantId, $betaald, $betalingscode,
        $betaalwijzeId, $annulatie, $annulatiedatum, $terugbetalingsCode, $bestellingsStatusId, $actiecodeGebruikt,
        $bedrijfsnaam, $btwNummer, $voornaam, $familienaam, $facturatieAdresId, $leveringsAdresId, $bestellijnen)
    {
        $this->bestelId = $bestelId;
        $this->besteldatum = $besteldatum;
        $this->klantId = $klantId;
        $this->betaald = $betaald;
        $this->betalingscode = $betalingscode;
        $this->betaalwijzeId = $betaalwijzeId;
        $this->annulatie = $annulatie;
        $this->annulatiedatum = $annulatiedatum;
        $this->terugbetalingsCode = $terugbetalingsCode;
        $this->bestellingsStatusId = $bestellingsStatusId;
        $this->actiecodeGebruikt = $actiecodeGebruikt;
        $this->bedrijfsnaam = $bedrijfsnaam;
        $this->btwNummer = $btwNummer;
        $this->voornaam = $voornaam;
        $this->familienaam = $familienaam;
        $this->facturatieAdresId = $facturatieAdresId;
        $this->leveringsAdresId = $leveringsAdresId;
        $this->bestellijnen = $bestellijnen;
    }

    public function getBestelId() : int
    {
        return $this->bestelId;
    }

    public function getBestelDatum()
    {
        return $this->besteldatum;
    }

    public function getKlantId() : int
    {
        return $this->klantId;
    }

    public function getBetaald() : int
    {
        return $this->betaald;
    }

    public function getBetalingsCode() : string
    {
        return $this->betalingscode;
    }

    public function getBetaalwijzeId() : int
    {
        return $this->betaalwijzeId;
    }

    public function getAnnulatie() : int
    {
        return $this->annulatie;
    }

    public function getAnnulatieDatum()
    {
        return $this->annulatiedatum;
    }

    public function getTerugbetalingsCode() : string
    {
        return $this->terugbetalingsCode;
    }

    public function getBestellingsStatusId() : int
    {
        return $this->bestellingsStatusId;
    }

    public function getActiecodeGebruikt() : int
    {
        return $this->actiecodeGebruikt;
    }

    public function getBedrijfsnaam() : string
    {
        return $this->bedrijfsnaam;
    }

    public function getBtwNummer() : string
    {
        return $this->btwNummer;
    }

    public function getVoornaam() : string
    {
        return $this->voornaam;
    }

    public function getFamilienaam() : string
    {
        return $this->familienaam;
    }

    public function getFacturatieAdresId() : int
    {
        return $this->facturatieAdresId;
    }

    public function getLeveringsAdresId() : int
    {
        return $this->leveringsAdresId;
    }

    public function getBestellijnen() : array
    {
        return $this->bestellijnen;
    }


}



