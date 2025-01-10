<?php
declare(strict_types=1);

class Review
{
    private int $id;
    private int $klantId;
    private int $score;
    private string $commentaar;
    private string $datum;
    private int $artikelId;

    public function __construct(
        int $id,
        int $klantId,
        int $score,
        string $commentaar,
        string $datum,
        int $artikelId
    ) {
        $this->id = $id;
        $this->klantId = $klantId;
        $this->score = $score;
        $this->commentaar = $commentaar;
        $this->datum = $datum;
        $this->artikelId = $artikelId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getKlantId(): int
    {
        return $this->klantId;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getCommentaar(): string
    {
        return $this->commentaar;
    }

    public function getDatum(): string
    {
        return $this->datum;
    }

    public function getArtikelId(): int
    {
        return $this->artikelId;
    }
}