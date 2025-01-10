<?php

declare(strict_types=1);
require_once('data/autoloader.php');
class ArtikelService
{
    public function getAlleArtikels(): array
    {
        $artikelDAO = new ArtikelDAO();
        $artikels = $artikelDAO->getAll();
        return $artikels;
    }

    public function getArtikelsPerPagina(int $paginaNummer): array
    {
        $artikelDAO = new ArtikelDAO();
        $artikels = $artikelDAO->getAlleArtikelsPerPagina($paginaNummer);
        return $artikels;
    }

    public function getAantalArtikels(
    ?string $zoekterm = null,
    ?int $categorieId = null,
    ?int $subCategorieId = null,
    ?int $hoofdCategorieId = null): int
    {
        $artikelDAO = new ArtikelDAO();
        return $artikelDAO->getAantalArtikels($zoekterm, $categorieId, $subCategorieId, $hoofdCategorieId);
    }

    public function getGemiddeldeScorePerArtikel(int $artikelId): ?float
    {
        $totaal = 0;
        $artikelDAO = new ArtikelDAO();
        $artikel = $artikelDAO->getArtikelById($artikelId);
        $reviews = $artikel->getReviews();
        if (count($reviews) > 0) {
            foreach ($reviews as $review) {
                $totaal += $review->getScore();
            }

            if (count($artikel->getReviews()) > 0) {
                $gemiddelde = round($totaal / count($artikel->getReviews()), 2);
            } else {
                $gemiddelde = 0;
            }

            return $gemiddelde;
        } else
            return null;
    }

    public function getDeMeesteVerkochteArtikels(): array
    {
        $artikelDAO = new ArtikelDAO();
        $artikels = $artikelDAO->getDeMeesteVerkochteArtikels();
        return $artikels;
    }

    public function getArtikelById(int $artikelId): ?Artikel
    {
        $artikelDAO = new ArtikelDAO();
        return $artikelDAO->getArtikelById($artikelId);
    }

    //============================BEGIN PIETER: ZOEKFUNCTIE=================================//
    public function zoekArtikelen($zoekterm)
    {
        $artikelDAO = new ArtikelDAO();
        return $artikelDAO->zoekArtikelen($zoekterm);
    }
    //============================EINDE PIETER: ZOEKFUNCTIE=================================//

    public function getCategorieen()
    {
        $artikelDAO = new ArtikelDAO();
        return $artikelDAO->getCategorieen();
    }

    public function getArtikelenbyCategorie($categorieId)
    {
        $artikelDAO = new ArtikelDAO();
        return $artikelDAO->getArtikelenbyCategorie($categorieId);
    }

    public function getArtikelenbyHoofdCategorie($hoofdCategorieId)
    {
        $artikelDAO = new ArtikelDAO();
        return $artikelDAO->getArtikelenbyHoofdCategorie($hoofdCategorieId);
    }


    public function getArtikelenGesorteerd(string $sorteerOp, string $sorteerRichting): array
    {
        $artikelDAO = new ArtikelDAO();
        return $artikelDAO->getArtikelenGesorteerd($sorteerOp, $sorteerRichting);
    }

    public function plaatsReview(string $auteur, int $score, string $commentaar, $datum, int $bestellijnId)
    {
        $reviewDAO = new ReviewDAO();
        $reviewDAO->plaatsReview($auteur, $score, $commentaar, $datum, $bestellijnId);
    }

    public function getReviewByArtikelId(int $artikelId): array
    {
        $reviewDAO = new ReviewDAO();
        return $reviewDAO->getReviewsPerArtikel($artikelId);
    }

    public function heeftKlantAlEenReviewGeplaatstVanDitArtikel(int $klantId, int $artikelId): bool
    {
        $reviewDAO = new ReviewDAO();

        $toegelaten = $reviewDAO->heeftKlantAlEenReviewGeplaatstVanDitArtikel($klantId, $artikelId);

        return $toegelaten;
    }


    public function getDeHoofdcategorieVanSubcategorie(int $categorieId): int
    {
        $artikelDAO = new ArtikelDAO();
        return $artikelDAO->getDeHoofdcategorieVanSubcategorie($categorieId);
    }

    public function getHoofdCategorieNaamByCategorieId(int $hoofdCategorieId) : string
    {
        $artikelDAO = new ArtikelDAO();
        return $artikelDAO->getHoofdCategorieNaamByCategorieId($hoofdCategorieId);
    }

    public function getArtikelenSpecifiek(
        $zoekterm,
        $categorieId,
        $hoofdCategorieId,
        $subCategorieId,
        $sorteerOp,
        $sorteerRichting,
        $paginaNummer,
        $maxAantalArtikelsPerPagina
    ) {
        $artikelDAO = new ArtikelDAO();
        return $artikelDAO->getArtikelenSpecifiek(
            $zoekterm,
            $categorieId,
            $hoofdCategorieId,
            $subCategorieId,
            $sorteerOp,
            $sorteerRichting,
            $paginaNummer,
            $maxAantalArtikelsPerPagina
        );
    }
    public function deleteReview(int $reviewId) 
    {
        $reviewDAO = new ReviewDAO();
        $reviewDAO->deleteReview($reviewId);
    }

    public function modifyReview(int $reviewId, int $newScore, string $newCommentaar)
    {
        $reviewDAO = new ReviewDAO();
        $reviewDAO->modifyReview($reviewId, $newScore, $newCommentaar);
    }

}
