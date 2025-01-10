<?php
declare(strict_types=1);

class Actiecode
{
    private int $actiecodeId;
    private string $actiecodeNaam;
    private string $actiecodeGeldigVanDatum;
    private string $actiecodeGeldigTotDatum;
    private int $actiecodeIsEenmalig;

    public function __construct(int $actiecodeId, string $actiecodeNaam, string $actiecodeGeldigVanDatum, string $actiecodeGeldigTotDatum, int $actiecodeIsEenmalig)
    {
        $this->actiecodeId = $actiecodeId;
        $this->actiecodeNaam = $actiecodeNaam;
        $this->actiecodeGeldigVanDatum = $actiecodeGeldigVanDatum;
        $this->actiecodeGeldigTotDatum = $actiecodeGeldigTotDatum;
        $this->actiecodeIsEenmalig = $actiecodeIsEenmalig;
    }

    public function getActiecodeId(): int
    {
        return $this->actiecodeId;
    }

    public function getActiecodeNaam(): string
    {
        return $this->actiecodeNaam;
    }

    public function getActiecodeGeldigVanDatum(): string
    {
        return $this->actiecodeGeldigVanDatum;
    }

    public function getActiecodeGeldigTotDatum(): string
    {
        return $this->actiecodeGeldigTotDatum;
    }

    public function getActiecodeIsEenmalig(): int
    {
        return $this->actiecodeIsEenmalig;
    }
}