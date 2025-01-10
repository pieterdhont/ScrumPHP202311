<?php
declare(strict_types=1);

class Adres
{
    /*Table 5: adressen*/
    /*facturatieAdresId en leveringsAdresId verwijst naar de primary key van de table adressen, zowel facturatieadres als leveradres moet hieruit worden gehaald*/
    private int $adresId; /*Id van het adres*/
    private string $straat; /*Straatnaam van het adres*/
    private string $huisnummer; /*Huisnummer van het adres*/
    private string $bus; /*Busnummer van het adres*/
    private int $plaatsId; /*PlaatsId van het adres*/
    private int $actief; /*Een 0 of 1 afhankelijk als dit adres nog actief is of niet*/


    /*Table 6: plaatsen*/
    /*gelinked aan table 5 via plaatsId*/
    private string $postcode; /*postcode van het adres*/
    private string $plaats; /*gemeente van het adres*/


    public function __construct(int $adresId, string $straat, string $huisnummer, string $bus, int $plaatsId, int $actief, string $postcode, string $plaats)
    {
        $this->adresId = $adresId;
        $this->straat = $straat;
        $this->huisnummer = $huisnummer;
        $this->bus = $bus;
        $this->plaatsId = $plaatsId;
        $this->actief = $actief;
        $this->postcode = $postcode;
        $this->plaats = $plaats;
    }

    public function getAdresId() : int
    {
        return $this->adresId;
    }
    public function getStraat() : string
    {
        return $this->straat;
    }
    public function getHuisnummer() : string
    {
        return $this->huisnummer;
    }
    public function getBus() : string
    {
        return $this->bus;
    }
    public function getPlaatsId() : int
    {
        return $this->plaatsId;
    }
    public function getActief() : int
    {
        return $this->actief;
    }
    public function getPostcode() : string
    {
        return $this->postcode;
    }
    public function getPlaats() : string
    {
        return $this->plaats;
    }

}