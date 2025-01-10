<?php
declare(strict_types=1);
require_once('data/autoloader.php');

class ReviewDAO
{
    public function getReviewsPerArtikel(int $artikelId): ?array
    {
        $sql = "
        select klantenreviews.klantenReviewId, bestellingen.klantId, klantenreviews.score, klantenreviews.commentaar, klantenreviews.datum, bestellijnen.artikelId from klantenreviews
        left join bestellijnen
        on klantenreviews.bestellijnId = bestellijnen.bestellijnId
        left join bestellingen
        on bestellijnen.bestelid = bestellingen.bestelId
        WHERE bestellijnen.artikelId = :artikelId";

        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array("artikelId" => $artikelId));
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $dbh = null;
        $reviews = array();


        if (count($resultSet) > 0) {
            foreach ($resultSet as $rij) {
                $review = new Review(
                    (int) $rij['klantenReviewId'],
                    (int) $rij['klantId'],
                    (int) $rij['score'],
                    (string) $rij['commentaar'],
                    (string) $rij['datum'],
                    (int) $rij['artikelId']
                );
                array_push($reviews, $review);
            }

            return $reviews;
        } else {
            return array();
        }
    }

    public function getReviewsByKlantId(int $klantId): array
    {
        $sql = '
        select klantenreviews.klantenReviewId, klantenreviews.nickname, klantenreviews.score, klantenreviews.commentaar, klantenreviews.datum, bestellijnen.artikelId, bestellingen.klantId from klantenreviews
        left join bestellijnen
        on klantenreviews.bestellijnId = bestellijnen.bestellijnId
        left join bestellingen
        on bestellijnen.bestelid = bestellingen.bestelId

        where bestellingen.klantId = :klantId';

        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array("klantId" => $klantId));
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $dbh = null;
        $reviews = array();

        if (count($resultSet) > 0) {
            foreach ($resultSet as $rij) {
                $review = new Review(
                    (int) $rij['klantenReviewId'],
                    (int) $rij['klantId'],
                    (int) $rij['score'],
                    (string) $rij['commentaar'],
                    (string) $rij['datum'],
                    (int) $rij['artikelId']
                );
                array_push($reviews, $review);
            }

            return $reviews;
        } else {
            return array();
        }


    }

    public function plaatsReview(string $auteur, int $score, string $commentaar, $datum, int $bestellijnId)
    {
        $sql = "insert into klantenreviews (nickname, score, commentaar, datum, bestellijnId) values (:nickname, :score, :commentaar, :datum, :bestellijnId)";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':nickname' => $auteur, ':score' => $score, ':commentaar' => $commentaar, ':datum' => $datum, ':bestellijnId' => $bestellijnId));
        $dbh = null;
    }

    public function heeftKlantAlEenReviewGeplaatstVanDitArtikel(int $klantId, int $artikelId): bool
    {
        $sql = '
        select klantenreviews.klantenReviewId, bestellijnen.artikelId, bestellingen.klantId from klantenreviews

        left join bestellijnen
        on klantenreviews.bestellijnId = bestellijnen.bestellijnId
        left join bestellingen
        on bestellijnen.bestelId = bestellingen.bestelId

        where artikelId = :artikelId and klantId = :klantId';

        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array("artikelId" => $artikelId, "klantId" => $klantId));
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $dbh = null;

        if (count($resultSet) <= 0) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteReview(int $reviewId)
    {
        $sql = "delete FROM klantenreviews WHERE klantenReviewId=" . $reviewId;
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
    }

    public function modifyReview(int $reviewId, int $newScore, string $newCommentaar)
    {
        //als resultaat bestaat, wijzig de gegevens
        $sql = "update klantenreviews set score = :score, commentaar = :commentaar where klantenReviewId = :klantenReviewId";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':score' => $newScore, ':commentaar' => $newCommentaar, ':klantenReviewId' => $reviewId));
        $dbh = null;
    }
}
