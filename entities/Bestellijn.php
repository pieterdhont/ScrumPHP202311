<?php
declare(strict_types=1);

class Bestellijn
{
    private int $bestellijnId;
    private int $bestelId;
    private int $artikelId;
    private int $aantalBesteld;
    private int $aantalGeannuleerd;

    public function __construct($bestellijnId, $bestelId, $artikelId, $aantalBesteld, $aantalGeannuleerd)
    {
        $this->bestellijnId = $bestellijnId;
        $this->bestelId = $bestelId;
        $this->artikelId = $artikelId;
        $this->aantalBesteld = $aantalBesteld;
        $this->aantalGeannuleerd = $aantalGeannuleerd;
    }

    public function getBestellijnId() : int
    {
        return $this->bestellijnId;
    }

    public function getBestelId() : int
    {
        return $this->bestelId;
    }

    public function getArtikelId() : int
    {
        return $this->artikelId;
    }

    public function getAantalBesteld() : int
    {
        return $this->aantalBesteld;
    }

    public function getAantalGeannuleerd() : int
    {
        return $this->aantalGeannuleerd;
    }
}

