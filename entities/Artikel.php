<?php
declare(strict_types=1);

class Artikel
{
    private int $id;
    private string $EAN;
    private string $naam;
    private string $beschrijving;
    private float $prijs;
    private int $gewichtInGram;
    private int $bestelPeil; /*Gaan we waarschijnlijk niet nodig hebben, maar ik zet het erbij in geval van uitbreiding*/
    private int $voorraad;
    private int $minimumVoorraad; /*Gaan we waarschijnlijk niet nodig hebben, maar ik zet het erbij in geval van uitbreiding*/
    private int $maximumVoorraad; /*Gaan we waarschijnlijk niet nodig hebben, maar ik zet het erbij in geval van uitbreiding*/
    private int $levertijd;
    private int $aantalBesteldLeverancier; /*Gaan we waarschijnlijk niet nodig hebben, maar ik zet het erbij in geval van uitbreiding*/
    private int $maxAantalInMagazijnPlaats; /*Gaan we waarschijnlijk niet nodig hebben, maar ik zet het erbij in geval van uitbreiding*/
    private int $leverancierId; /*Gebruiken voor de leveranciersinformatie op te halen van het artikel*/

    /*Categoriegegevens ophalen via het categorieId van elk artikel*/
    private int $categorieId; /*Gebruiken voor de categorie informatie op te halen*/
    private string $categorieNaam;
    private int $hoofdCategorieId;
    private string $leverancierNaam;
    private array $scores;

    private array $reviews;

    public function __construct(
        int $id,
        string $EAN,
        string $naam,
        string $beschrijving,
        float $prijs,
        int $gewichtInGram,
        int $bestelPeil,
        int $voorraad,
        int $minimumVoorraad,
        int $maximumVoorraad,
        int $levertijd,
        int $aantalBesteldLeverancier,
        int $maxAantalInMagazijnPlaats,
        int $leverancierId,
        int $categorieId,
        string $categorieNaam,
        int $hoofdCategorieId,
        string $leverancierNaam,
        array $reviews
    ) {
        $this->id = $id;
        $this->EAN = $EAN;
        $this->naam = $naam;
        $this->beschrijving = $beschrijving;
        $this->prijs = $prijs;
        $this->gewichtInGram = $gewichtInGram;
        $this->bestelPeil = $bestelPeil;
        $this->voorraad = $voorraad;
        $this->minimumVoorraad = $minimumVoorraad;
        $this->maximumVoorraad = $maximumVoorraad;
        $this->levertijd = $levertijd;
        $this->aantalBesteldLeverancier = $aantalBesteldLeverancier;
        $this->maxAantalInMagazijnPlaats = $maxAantalInMagazijnPlaats;
        $this->leverancierId = $leverancierId;
        $this->categorieId = $categorieId;
        $this->categorieNaam = $categorieNaam;
        $this->hoofdCategorieId = $hoofdCategorieId;
        $this->leverancierNaam = $leverancierNaam;
        $this->reviews = $reviews;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEAN(): string
    {
        return $this->EAN;
    }

    public function getNaam(): string
    {
        return $this->naam;
    }

    public function getBeschrijving(): string
    {
        return $this->beschrijving;
    }

    public function getPrijs(): float
    {
        return $this->prijs;
    }

    public function getGewicht(): int
    {
        return $this->gewichtInGram;
    }

    public function getBestelPeil(): int
    {
        return $this->bestelPeil;
    }

    public function getVoorraad(): int
    {
        return $this->voorraad;
    }

    public function getMinimumVoorraad(): int
    {
        return $this->minimumVoorraad;
    }

    public function getMaximumVoorraad(): int
    {
        return $this->maximumVoorraad;
    }

    public function getLevertijd(): int
    {
        return $this->levertijd;
    }

    public function getAantalBesteldLeverancier(): int
    {
        return $this->aantalBesteldLeverancier;
    }

    public function getMaxAantalInMagazijnPlaats(): int
    {
        return $this->maxAantalInMagazijnPlaats;
    }

    public function getLeverancierId(): int
    {
        return $this->leverancierId;
    }

    public function getCategorieId(): int
    {
        return $this->categorieId;
    }

    public function getCategorieNaam(): string
    {
        return $this->categorieNaam;
    }

    public function getHoofdCategorieId(): int
    {
        return $this->hoofdCategorieId;
    }

    public function getArtikelLeverancierNaam(): string
    {
        return $this->leverancierNaam;
    }

    public function getReviews(): array
    {
        return $this->reviews;
    }
}