<?php
//Gebruiker.php
declare(strict_types=1);

class Gebruiker
{
    /*Table 1: gebruikersaccounts - hoofdtable*/
    private int $gebruikersAccountId; /*Id van het gebruikersaccount*/
    private string $emailadres; /*email adres van het gebruikersaccount voor in te loggen*/
    private string $paswoord; /*wachtwoord van het gebruikersaccount voor in te loggen, geëncrypteerd*/
    private int $disabled; /*Een 0 of 1, is 1 wanneer het account disabled is en voorkomt dat de user kan inloggen*/

    /*Table 2: natuurlijkepersonen gelinked via gebruikersAccountId*/
    private int $klantId; /*Het klantennummer van dit account*/
    private string $voornaam; /*voornaam van de klant*/
    private string $familienaam; /*familienaam van de klant*/

    /*Table 3: rechtspersonen*/
    /*Als het klantId van natuurlijkepersonen bestaat in deze table, moeten deze gegevens ook opgehaald worden*/
    private string $bedrijfsNaam; /*DIT STAAT GEWOON ALS 'naam' in de rechtspersonen table! Opletten met het ophalen van dit gegeven*/
    private string $BTWNummer; /*Btw nummer van de klant als deze een rechtspersoon is*/

    /*Table 4: klanten */
    private Adres $facturatieAdres; /*Het facturatie adres als object*/
    private Adres $leveringsAdres; /*Het leverings adres als object*/




    /*Dit Gebruiker object moet later uitgebreid worden met een bestelling object voor het raadplegen van zijn/haar bestellingen*/

    public function __construct(
        int $gebruikersAccountId,
        string $emailadres,
        string $paswoord,
        int $disabled,
        int $klantId,
        string $voornaam,
        string $familienaam,
        string $bedrijfsNaam,
        string $BTWNummer,
        Adres $facturatieAdres,
        Adres $leveringsAdres
    ) {
        $this->gebruikersAccountId = $gebruikersAccountId;
        $this->emailadres = $emailadres;
        $this->paswoord = $paswoord;
        $this->disabled = $disabled;
        $this->klantId = $klantId;
        $this->voornaam = $voornaam;
        $this->familienaam = $familienaam;
        $this->bedrijfsNaam = $bedrijfsNaam;
        $this->BTWNummer = $BTWNummer;
        $this->facturatieAdres = $facturatieAdres; 
        $this->leveringsAdres = $leveringsAdres;
    }

    public function getGebruikersAccountId() : int
    {
        return $this->gebruikersAccountId;
    }
    public function getEmailadres() : string
    {
        return $this->emailadres;
    }
    public function getPaswoord() : string
    {
        return $this->paswoord;
    }
    public function getDisabled() : int
    {
        return $this->disabled;
    }
    public function getKlantId() : int
    {
        return $this->klantId;
    }
    public function getVoornaam() : string
    {
        return $this->voornaam;
    }
    public function getFamilienaam() : string
    {
        return $this->familienaam;
    }
    public function getBedrijfsnaam() : string
    {
        return $this->bedrijfsNaam;
    }
    public function getBTWNummer() : string
    {
        return $this->BTWNummer;
    }
    public function getFacturatieAdres() : Adres
    {
        return $this->facturatieAdres;
    }

    public function getLeveringsAdres() : Adres
    {
        return $this->leveringsAdres;
    }
}