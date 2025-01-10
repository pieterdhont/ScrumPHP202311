<?php

declare(strict_types=1);
require_once('data/autoloader.php');

class ArtikelDAO
{
    public function getAll(): array
    {
        $sql = "
        SELECT
        artikelen.artikelId,
        artikelen.ean,
        artikelen.naam as artikelNaam,
        artikelen.beschrijving,
        artikelen.prijs,
        artikelen.gewichtInGram,
        artikelen.bestelpeil,
        artikelen.voorraad,
        artikelen.minimumVoorraad,
        artikelen.maximumVoorraad,
        artikelen.levertijd,
        artikelen.aantalBesteldLeverancier,
        artikelen.maxAantalInMagazijnPLaats,
        artikelen.leveranciersId,
        categorieen.categorieId,
        categorieen.naam as categorieNaam,
        categorieen.hoofdCategorieId,
        leveranciers.naam as leverancierNaam
        FROM artikelen
        LEFT JOIN artikelcategorieen
        ON artikelcategorieen.artikelId = artikelen.artikelId
        LEFT JOIN categorieen
        ON artikelcategorieen.categorieId = categorieen.categorieId
        LEFT JOIN leveranciers
        ON artikelen.leveranciersId = leveranciers.leveranciersId
        GROUP BY artikelen.artikelId         
        ";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSet = $dbh->query($sql);
        $lijst = array();
        foreach ($resultSet as $rij) {
            $reviewDAO = new ReviewDAO();
            $reviews = $reviewDAO->getReviewsPerArtikel($rij['artikelId']);
            $artikel = new Artikel(
                (int) $rij['artikelId'],
                (string) $rij['ean'],
                (string) $rij['artikelNaam'],
                (string) $rij['beschrijving'],
                (float) $rij['prijs'],
                (int) $rij['gewichtInGram'],
                (int) $rij['bestelpeil'],
                (int) $rij['voorraad'],
                (int) $rij['minimumVoorraad'],
                (int) $rij['maximumVoorraad'],
                (int) $rij['levertijd'],
                (int) $rij['aantalBesteldLeverancier'],
                (int) $rij['maxAantalInMagazijnPLaats'],
                (int) $rij['leveranciersId'],
                (int) $rij['categorieId'],
                (string) $rij['categorieNaam'],
                (int) $rij['hoofdCategorieId'],
                (string) $rij['leverancierNaam'],
                $reviews
            );
            array_push($lijst, $artikel);
        }
        $dbh = null;

        return $lijst;
    }

    public function getAantalArtikels(
    ?string $zoekterm = null,
    ?int $categorieId = null,
    ?int $subCategorieId = null,
    ?int $hoofdCategorieId = null): int
    {
        $parameters = [];
        $sql = "
        SELECT
        count(DISTINCT a.artikelId) as aantalArtikels
    FROM
        Artikelen a
    LEFT JOIN ArtikelCategorieen ac ON a.artikelId = ac.artikelId
    LEFT JOIN Categorieen c ON ac.categorieId = c.categorieId
    WHERE 1 = 1
    ";

        // Filter op zoekterm
        if (!is_null($zoekterm)) {
            $sql .= " AND a.naam LIKE :zoekterm ";
            $parameters[':zoekterm'] = '%' . $zoekterm . '%';
        }

        // Filter op categorie
        if (!is_null($categorieId)) {
            $sql .= " AND ac.categorieId = :categorieId ";
            $parameters[':categorieId'] = $categorieId;
        }

        // Filter op hoofdcategorie
        if (!is_null($hoofdCategorieId)) {
            $sql .= " AND c.hoofdCategorieId = :hoofdCategorieId ";
            $parameters[':hoofdCategorieId'] = $hoofdCategorieId;
        }

         // Filter op subcategorie
         if (!is_null($subCategorieId)) {
            $sql .= " AND c.categorieId = :subCategorieId ";
            $parameters[':subCategorieId'] = $subCategorieId;
        }

        // Uitvoeren van de query
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        foreach ($parameters as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        $dbh = null;
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbh = null;
        return $result['aantalArtikels'];
    }

    public function getAlleArtikelsPerPagina(int $paginaNummer): array
    {
        $offset = ($paginaNummer - 1) * 50;
        $sql = "
        SELECT
        artikelen.artikelId,
        artikelen.ean,
        artikelen.naam as artikelNaam,
        artikelen.beschrijving,
        artikelen.prijs,
        artikelen.gewichtInGram,
        artikelen.bestelpeil,
        artikelen.voorraad,
        artikelen.minimumVoorraad,
        artikelen.maximumVoorraad,
        artikelen.levertijd,
        artikelen.aantalBesteldLeverancier,
        artikelen.maxAantalInMagazijnPLaats,
        artikelen.leveranciersId,
        categorieen.categorieId,
        categorieen.naam as categorieNaam,
        categorieen.hoofdCategorieId,
        leveranciers.naam as leverancierNaam
        FROM artikelen
        LEFT JOIN artikelcategorieen
        ON artikelcategorieen.artikelId = artikelen.artikelId
        LEFT JOIN categorieen
        ON artikelcategorieen.categorieId = categorieen.categorieId
        LEFT JOIN leveranciers
        ON artikelen.leveranciersId = leveranciers.leveranciersId
        GROUP BY artikelen.artikelId 
        LIMIT " . (int)$offset . ", 50";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $lijst = array();
        foreach ($resultSet as $rij) {
            $reviewDAO = new ReviewDAO();
            $reviews = $reviewDAO->getReviewsPerArtikel($rij['artikelId']);
            $artikel = new Artikel(
                (int) $rij['artikelId'],
                (string) $rij['ean'],
                (string) $rij['artikelNaam'],
                (string) $rij['beschrijving'],
                (float) $rij['prijs'],
                (int) $rij['gewichtInGram'],
                (int) $rij['bestelpeil'],
                (int) $rij['voorraad'],
                (int) $rij['minimumVoorraad'],
                (int) $rij['maximumVoorraad'],
                (int) $rij['levertijd'],
                (int) $rij['aantalBesteldLeverancier'],
                (int) $rij['maxAantalInMagazijnPLaats'],
                (int) $rij['leveranciersId'],
                (int) $rij['categorieId'],
                (string) $rij['categorieNaam'],
                (int) $rij['hoofdCategorieId'],
                (string) $rij['leverancierNaam'],
                $reviews
            );
            array_push($lijst, $artikel);
        }
        $dbh = null;

        return $lijst;
    }

    public function getArtikelById(int $artikelId): ?Artikel
    {
        $sql = "
        SELECT
        artikelen.artikelId,
        artikelen.ean,
        artikelen.naam as artikelNaam,
        artikelen.beschrijving,
        artikelen.prijs,
        artikelen.gewichtInGram,
        artikelen.bestelpeil,
        artikelen.voorraad,
        artikelen.minimumVoorraad,
        artikelen.maximumVoorraad,
        artikelen.levertijd,
        artikelen.aantalBesteldLeverancier,
        artikelen.maxAantalInMagazijnPLaats,
        artikelen.leveranciersId,
        categorieen.categorieId,
        categorieen.naam as categorieNaam,
        categorieen.hoofdCategorieId,
        leveranciers.naam as leverancierNaam
        FROM artikelen
        LEFT JOIN artikelcategorieen
        ON artikelcategorieen.artikelId = artikelen.artikelId
        LEFT JOIN categorieen
        ON artikelcategorieen.categorieId = categorieen.categorieId
        LEFT JOIN leveranciers
        ON artikelen.leveranciersId = leveranciers.leveranciersId
        WHERE artikelen.artikelId = :artikelId           
        ";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam("artikelId", $artikelId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbh = null;

        if ($result) {
            $reviewDAO = new ReviewDAO();
            $reviews = $reviewDAO->getReviewsPerArtikel($result['artikelId']);
            $artikel = new Artikel(
                (int) $result['artikelId'],
                (string) $result['ean'],
                (string) $result['artikelNaam'],
                (string) $result['beschrijving'],
                (float) $result['prijs'],
                (int) $result['gewichtInGram'],
                (int) $result['bestelpeil'],
                (int) $result['voorraad'],
                (int) $result['minimumVoorraad'],
                (int) $result['maximumVoorraad'],
                (int) $result['levertijd'],
                (int) $result['aantalBesteldLeverancier'],
                (int) $result['maxAantalInMagazijnPLaats'],
                (int) $result['leveranciersId'],
                (int) $result['categorieId'],
                (string) $result['categorieNaam'],
                (int) $result['hoofdCategorieId'],
                (string) $result['leverancierNaam'],
                $reviews
            );

            return $artikel;
        } else {
            return null;
        }
    }

    //============================BEGIN PIETER: ZOEKFUNCTIE=================================//
    public function zoekArtikelen(string $zoekterm): array
    {
        $sql = "
        SELECT
        artikelen.artikelId,
        artikelen.ean,
        artikelen.naam,
        artikelen.beschrijving,
        artikelen.prijs,
        artikelen.gewichtInGram,
        artikelen.bestelpeil,
        artikelen.voorraad,
        artikelen.minimumVoorraad,
        artikelen.maximumVoorraad,
        artikelen.levertijd,
        artikelen.aantalBesteldLeverancier,
        artikelen.maxAantalInMagazijnPLaats,
        artikelen.leveranciersId,
        leveranciers.naam AS leveranciersNaam
        FROM artikelen
        LEFT JOIN leveranciers
        ON artikelen.leveranciersId = leveranciers.leveranciersId
        WHERE artikelen.naam LIKE :zoekterm
        ";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':zoekterm', '%' . $zoekterm . '%');
        $stmt->execute();

        $resultaten = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $lijst = array();
        foreach ($resultaten as $rij) {
            $reviewDAO = new ReviewDAO();
            $reviews = $reviewDAO->getReviewsPerArtikel($rij['artikelId']);
            $artikel = new Artikel(
                (int) $rij['artikelId'],
                (string) $rij['ean'],
                (string) $rij['naam'],
                (string) $rij['beschrijving'],
                (float) $rij['prijs'],
                (int) $rij['gewichtInGram'],
                (int) $rij['bestelpeil'],
                (int) $rij['voorraad'],
                (int) $rij['minimumVoorraad'],
                (int) $rij['maximumVoorraad'],
                (int) $rij['levertijd'],
                (int) $rij['aantalBesteldLeverancier'],
                (int) $rij['maxAantalInMagazijnPLaats'],
                (int) $rij['leveranciersId'],
                // De volgende waarden zijn niet beschikbaar in de query, dus worden standaardwaarden gebruikt
                0, // categorieId
                '', // categorieNaam
                0, // hoofdCategorieId
                (string) $rij['leveranciersNaam'],
                $reviews
            );
            array_push($lijst, $artikel);
        }
        $dbh = null;
        return $lijst;
    }

    //============================EINDE PIETER: ZOEKFUNCTIE=================================//

    //============================BEGIN: Spotlight Functies================================//
    public function getDeMeesteVerkochteArtikels(): array
    {
        $lijst = array();
        $sql = "
        SELECT
        artikelen.artikelId, 
        bestellijnen.artikelId, 
        artikelen.ean, 
        artikelen.naam, 
        artikelen.beschrijving, 
        artikelen.prijs, 
        artikelen.gewichtInGram, 
        artikelen.bestelpeil, 
        artikelen.voorraad, 
        artikelen.minimumVoorraad, 
        artikelen.maximumVoorraad, 
        artikelen.levertijd, 
        artikelen.aantalBesteldLeverancier, 
        artikelen.maxAantalInMagazijnPLaats, 
        artikelen.leveranciersId,
        SUM(bestellijnen.aantalBesteld) as totaalVerkocht 
        from bestellijnen, artikelen 
        WHERE artikelen.artikelId = bestellijnen.artikelId
        group by bestellijnen.artikelId
        order by totaalVerkocht desc
        LIMIT 0, 10";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($resultSet as $rij) {
            $reviewDAO = new ReviewDAO();
            $reviews = $reviewDAO->getReviewsPerArtikel($rij['artikelId']);
            $artikel = new Artikel(
                (int) $rij['artikelId'],
                (string) $rij['ean'],
                (string) $rij['naam'],
                (string) $rij['beschrijving'],
                (float) $rij['prijs'],
                (int) $rij['gewichtInGram'],
                (int) $rij['bestelpeil'],
                (int) $rij['voorraad'],
                (int) $rij['minimumVoorraad'],
                (int) $rij['maximumVoorraad'],
                (int) $rij['levertijd'],
                (int) $rij['aantalBesteldLeverancier'],
                (int) $rij['maxAantalInMagazijnPLaats'],
                (int) $rij['leveranciersId'],
                -1, //SubCategorie id zal niet nodig om de spotlight te tonen
                '', //SubCategorie naam zal niet nodig om de spotlight te tonen
                -1, //HoofdCategorie id zal niet nodig om de spotlight te tonen
                '', //HoofdCategorie naam zal niet nodig om de spotlight te tonen
                $reviews
            );
            array_push($lijst, $artikel);
        }

        return $lijst;
    }

    //============================EINDE: Spotlight Functies=================================//

    // =========== Ophalen categorieën =============== //

    public function getCategorieen(): array
    {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);

        $categorieen = array();

        $stmt = $dbh->prepare("SELECT * FROM categorieen WHERE hoofdCategorieId IS NULL");
        $stmt->execute();
        $hoofdCategorieen = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($hoofdCategorieen as $hoofdCategorie) {
            $hoofdId = $hoofdCategorie['categorieId'];
            $hoofdNaam = $hoofdCategorie['naam'];

            $stmt = $dbh->prepare("SELECT categorieId, naam FROM categorieen WHERE hoofdCategorieId = :hoofdId");
            $stmt->bindParam(':hoofdId', $hoofdId);
            $stmt->execute();
            $subCategorieen = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $categorieen[$hoofdId] = array(
                'naam' => $hoofdNaam,
                'subcategorieen' => array()
            );

            foreach ($subCategorieen as $subCategorie) {
                $subId = $subCategorie['categorieId'];
                $subNaam = $subCategorie['naam'];

                $stmt = $dbh->prepare("SELECT categorieId, naam FROM categorieen WHERE hoofdCategorieId = :subId");
                $stmt->bindParam(':subId', $subId);
                $stmt->execute();
                $subSubCategorieen = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $categorieen[$hoofdId]['subcategorieen'][$subId] = array(
                    'naam' => $subNaam,
                    'subSubcategorieen' => $subSubCategorieen
                );
            }
        }
        return $categorieen;
    }

    // getArtikelenbyCategorie voor gebruik bij de div met ul "categorieen"

    public function getArtikelenbyCategorie(int $categorieId): array
    {
        $sql = "
        SELECT
        artikelen.artikelId,
        artikelen.ean,
        artikelen.naam,
        artikelen.beschrijving,
        artikelen.prijs,
        artikelen.gewichtInGram,
        artikelen.bestelpeil,
        artikelen.voorraad,
        artikelen.minimumVoorraad,
        artikelen.maximumVoorraad,
        artikelen.levertijd,
        artikelen.aantalBesteldLeverancier,
        artikelen.maxAantalInMagazijnPLaats,
        artikelen.leveranciersId,
        leveranciers.naam AS leveranciersNaam
        FROM artikelen
        LEFT JOIN artikelcategorieen 
        ON artikelen.artikelId = artikelcategorieen.artikelId
        LEFT JOIN leveranciers
        ON artikelen.leveranciersId = leveranciers.leveranciersId
        WHERE artikelcategorieen.categorieId = :categorieId
        ";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':categorieId', $categorieId);
        $stmt->execute();

        $resultaten = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $lijst = array();
        foreach ($resultaten as $rij) {
            $reviewDAO = new ReviewDAO();
            $reviews = $reviewDAO->getReviewsPerArtikel($rij['artikelId']);
            $artikel = new Artikel(
                (int) $rij['artikelId'],
                (string) $rij['ean'],
                (string) $rij['naam'],
                (string) $rij['beschrijving'],
                (float) $rij['prijs'],
                (int) $rij['gewichtInGram'],
                (int) $rij['bestelpeil'],
                (int) $rij['voorraad'],
                (int) $rij['minimumVoorraad'],
                (int) $rij['maximumVoorraad'],
                (int) $rij['levertijd'],
                (int) $rij['aantalBesteldLeverancier'],
                (int) $rij['maxAantalInMagazijnPLaats'],
                (int) $rij['leveranciersId'],
                // De volgende waarden zijn niet beschikbaar in de query, dus worden standaardwaarden gebruikt
                0, // categorieId
                '', // categorieNaam
                0, // hoofdCategorieId
                (string) $rij['leveranciersNaam'],
                $reviews
            );
            array_push($lijst, $artikel);
        }
        $dbh = null;
        return $lijst;
    }

    public function getArtikelenbyHoofdCategorie(int $hoofdCategorieId): array
    {
        $sql = "
        SELECT
        artikelen.artikelId,
        artikelen.ean,
        artikelen.naam,
        artikelen.beschrijving,
        artikelen.prijs,
        artikelen.gewichtInGram,
        artikelen.bestelpeil,
        artikelen.voorraad,
        artikelen.minimumVoorraad,
        artikelen.maximumVoorraad,
        artikelen.levertijd,
        artikelen.aantalBesteldLeverancier,
        artikelen.maxAantalInMagazijnPLaats,
        artikelen.leveranciersId,
        leveranciers.naam AS leveranciersNaam
        FROM artikelen
        LEFT JOIN artikelcategorieen 
        ON artikelen.artikelId = artikelcategorieen.artikelId
        LEFT JOIN categorieen
        ON artikelcategorieen.categorieId = categorieen.categorieId
        LEFT JOIN leveranciers
        ON artikelen.leveranciersId = leveranciers.leveranciersId
        WHERE categorieen.hoofdCategorieId = :hoofdCategorieId
        ";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':hoofdCategorieId', $hoofdCategorieId);
        $stmt->execute();

        $resultaten = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $lijst = array();
        foreach ($resultaten as $rij) {
            $reviewDAO = new ReviewDAO();
            $reviews = $reviewDAO->getReviewsPerArtikel($rij['artikelId']);
            $artikel = new Artikel(
                (int) $rij['artikelId'],
                (string) $rij['ean'],
                (string) $rij['naam'],
                (string) $rij['beschrijving'],
                (float) $rij['prijs'],
                (int) $rij['gewichtInGram'],
                (int) $rij['bestelpeil'],
                (int) $rij['voorraad'],
                (int) $rij['minimumVoorraad'],
                (int) $rij['maximumVoorraad'],
                (int) $rij['levertijd'],
                (int) $rij['aantalBesteldLeverancier'],
                (int) $rij['maxAantalInMagazijnPLaats'],
                (int) $rij['leveranciersId'],
                // De volgende waarden zijn niet beschikbaar in de query, dus worden standaardwaarden gebruikt
                0, // categorieId
                '', // categorieNaam
                0, // hoofdCategorieId
                (string) $rij['leveranciersNaam'],
                $reviews
            );
            array_push($lijst, $artikel);
        }
        $dbh = null;
        return $lijst;
    }


    public function getArtikelenGesorteerd(string $sorteerOp, string $sorteerRichting): array
    {
        // Valideer de invoer
        if (!in_array($sorteerOp, ['prijs', 'naam', 'score']) || !in_array($sorteerRichting, ['ASC', 'DESC'])) {
            $sorteerOp = 'naam';
            $sorteerRichting = 'ASC';
        }

        // Bepaal de kolomnaam voor het sorteren
        $sorteerKolom = "artikelen.naam";
        if ($sorteerOp == "prijs") {
            $sorteerKolom = "artikelen.prijs";
        } elseif ($sorteerOp == "score") {
            $sorteerKolom = "avg_reviews.gemiddeldeScore";
        }

        $sql = "
        SELECT
        artikelen.artikelId,
        artikelen.ean,
        artikelen.naam as artikelNaam,
        artikelen.beschrijving,
        artikelen.prijs,
        artikelen.gewichtInGram,
        artikelen.bestelpeil,
        artikelen.voorraad,
        artikelen.minimumVoorraad,
        artikelen.maximumVoorraad,
        artikelen.levertijd,
        artikelen.aantalBesteldLeverancier,
        artikelen.maxAantalInMagazijnPLaats,
        artikelen.leveranciersId,
        IFNULL(avg_reviews.gemiddeldeScore, 0) as gemiddeldeScore
        FROM artikelen
        LEFT JOIN (
            SELECT bestellijnen.artikelId, AVG(klantenreviews.score) as gemiddeldeScore
            FROM klantenreviews
            JOIN bestellijnen ON klantenreviews.bestellijnId = bestellijnen.bestellijnId
            GROUP BY bestellijnen.artikelId
        ) as avg_reviews ON avg_reviews.artikelId = artikelen.artikelId
        GROUP BY artikelen.artikelId
        ORDER BY " . $sorteerKolom . " " . $sorteerRichting;

        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSet = $dbh->query($sql);
        $lijst = array();
        foreach ($resultSet as $rij) {
            $reviewDAO = new ReviewDAO();
            $reviews = $reviewDAO->getReviewsPerArtikel($rij['artikelId']);
            $artikel = new Artikel(
                (int) $rij['artikelId'],
                (string) $rij['ean'],
                (string) $rij['artikelNaam'],
                (string) $rij['beschrijving'],
                (float) $rij['prijs'],
                (int) $rij['gewichtInGram'],
                (int) $rij['bestelpeil'],
                (int) $rij['voorraad'],
                (int) $rij['minimumVoorraad'],
                (int) $rij['maximumVoorraad'],
                (int) $rij['levertijd'],
                (int) $rij['aantalBesteldLeverancier'],
                (int) $rij['maxAantalInMagazijnPLaats'],
                (int) $rij['leveranciersId'],
                // Onderstaande velden worden niet opgehaald uit de database in deze query
                0, // categorieId
                '', // categorieNaam
                0, // hoofdCategorieId
                '', // leverancierNaam (tenzij nodig)
                $reviews
            );
            array_push($lijst, $artikel);
        }
        $dbh = null;

        return $lijst;
    }

    public function getArtikelenSpecifiek(
        ?string $zoekterm = null,
        ?int $categorieId = null,
        ?int $hoofdCategorieId = null,
        ?int $subCategorieId = null,
        string $sorteerOp = null,
        string $sorteerRichting = null,
        int $paginaNummer,
        int $maxAantalArtikelsPerPagina
    ): array {
        $offset = ($paginaNummer - 1) * $maxAantalArtikelsPerPagina;
        $parameters = [];
        $sql = "
            SELECT
                a.artikelId,
                a.ean,
                a.naam as artikelNaam,
                a.beschrijving,
                a.prijs,
                a.gewichtInGram,
                a.bestelpeil,
                a.voorraad,
                a.minimumVoorraad,
                a.maximumVoorraad,
                a.levertijd,
                a.aantalBesteldLeverancier,
                a.maxAantalInMagazijnPLaats,
                a.leveranciersId,
                l.naam as leverancierNaam,
                c.categorieId,
                c.naam as categorieNaam,
                c.hoofdCategorieId,
                IFNULL(avg_reviews.gemiddeldeScore, 0) as gemiddeldeScore
            FROM Artikelen a
            LEFT JOIN Leveranciers l ON a.leveranciersId = l.leveranciersId
            LEFT JOIN ArtikelCategorieen ac ON a.artikelId = ac.artikelId
            LEFT JOIN Categorieen c ON ac.categorieId = c.categorieId
            LEFT JOIN (
                SELECT
                    b.artikelId,
                    AVG(kl.score) as gemiddeldeScore
                FROM
                    KlantenReviews kl
                INNER JOIN Bestellijnen b ON kl.bestellijnId = b.bestellijnId
                GROUP BY
                    b.artikelId
            ) avg_reviews ON a.artikelId = avg_reviews.artikelId
            WHERE 1 = 1
        ";

        // Filter op zoekterm
        if (!is_null($zoekterm)) {
            $sql .= " AND a.naam LIKE :zoekterm ";
            $parameters[':zoekterm'] = '%' . $zoekterm . '%';
        }

        // Filter op categorie
        if (!is_null($categorieId)) {
            $sql .= " AND (c.categorieId = :categorieId OR c.hoofdCategorieId = :categorieId)";
            $parameters[':categorieId'] = $categorieId;
        }

        // Filter op hoofdcategorie
        if (!is_null($hoofdCategorieId)) {
            $sql .= " AND c.hoofdCategorieId = :hoofdCategorieId ";
            $parameters[':hoofdCategorieId'] = $hoofdCategorieId;
        }

        // Filter op subcategorie
        if (!is_null($subCategorieId)) {
            $sql .= " AND c.categorieId = :subCategorieId ";
            $parameters[':subCategorieId'] = $subCategorieId;
        }

        $sql .= " GROUP BY a.artikelId";

        // Sorteerlogica
        $sorteerKolommen = ['naam' => 'a.naam', 'prijs' => 'a.prijs', 'score' => 'avg_reviews.gemiddeldeScore'];
        if (!is_null($sorteerOp)) {
            $sorteerKolom = $sorteerKolommen[$sorteerOp];
            $sql .= " ORDER BY $sorteerKolom $sorteerRichting";
        }
        
        // Limit toevoegen
        $sql .= " LIMIT " . (int)$offset . ", ". (int)$maxAantalArtikelsPerPagina. ";";

        // Uitvoeren van de query
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        foreach ($parameters as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        $resultaten = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $dbh = null;

        // Omzetten van resultaten naar Artikel objecten
        $lijst = [];
        foreach ($resultaten as $rij) {
            $reviewDAO = new ReviewDAO();
            $reviews = $reviewDAO->getReviewsPerArtikel($rij['artikelId']);
            $artikel = new Artikel(
                (int) $rij['artikelId'],
                (string) $rij['ean'],
                (string) $rij['artikelNaam'],
                (string) $rij['beschrijving'],
                (float) $rij['prijs'],
                (int) $rij['gewichtInGram'],
                (int) $rij['bestelpeil'],
                (int) $rij['voorraad'],
                (int) $rij['minimumVoorraad'],
                (int) $rij['maximumVoorraad'],
                (int) $rij['levertijd'],
                (int) $rij['aantalBesteldLeverancier'],
                (int) $rij['maxAantalInMagazijnPLaats'],
                (int) $rij['leveranciersId'],
                (int) $rij['categorieId'],
                (string) $rij['categorieNaam'],
                (int) $rij['hoofdCategorieId'],
                (string) $rij['leverancierNaam'],
                $reviews
            );
            array_push($lijst, $artikel);
        }

        return $lijst;
    }

    public function getDeHoofdcategorieVanSubcategorie(int $categorieId): int
    {
        $sql = "SELECT hoofdCategorieId FROM categorieen WHERE categorieId = :categorieId;";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam("categorieId", $categorieId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbh = null;
        return $result['hoofdCategorieId'];
    }

    public function getHoofdCategorieNaamByCategorieId(int $hoofdCategorieId): string
    {
        $sql = "select naam from categorieen where categorieId = :hoofdCategorieId";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam("hoofdCategorieId", $hoofdCategorieId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbh = null;
        return $result['naam'];
    }
}
