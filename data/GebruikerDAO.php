<?php
//GebruikerDAO.php
declare(strict_types=1);
require_once('data/autoloader.php');

class GebruikerDAO
{
    public function getAll(): array
    {
        $adresDAO = new AdresDAO;
        $sql = "
        SELECT 
        gebruikersaccounts.gebruikersAccountId, 
        gebruikersaccounts.emailadres, 
        gebruikersaccounts.paswoord, 
        gebruikersaccounts.disabled,
        natuurlijkepersonen.klantId,
        natuurlijkepersonen.voornaam,
        natuurlijkepersonen.familienaam,
        rechtspersonen.naam,
        rechtspersonen.btwNummer,
        klanten.facturatieAdresId,
        klanten.leveringsAdresId
        FROM gebruikersaccounts
        LEFT JOIN natuurlijkepersonen
        ON gebruikersaccounts.gebruikersAccountId = natuurlijkepersonen.gebruikersAccountId
        LEFT JOIN rechtspersonen
        ON natuurlijkepersonen.klantId = rechtspersonen.klantId
        LEFT JOIN klanten
        ON natuurlijkepersonen.klantId = klanten.klantId
        ";
        /*try {*/
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSet = $dbh->query($sql);
        $lijst = array();
        foreach ($resultSet as $rij) {
            $gebruiker = new Gebruiker(
                (int) $rij['gebruikersAccountId'],
                (string) $rij['emailadres'],
                (string) $rij['paswoord'],
                (int) $rij['disabled'],
                (int) $rij['klantId'],
                (string) $rij['voornaam'],
                (string) $rij['familienaam'],
                (string) $rij['naam'],
                (string) $rij['btwNummer'],
                $adresDAO->getAdresById((int) $rij['facturatieAdresId']),
                $adresDAO->getAdresById((int) $rij['leveringsAdresId']),
            );
            array_push($lijst, $gebruiker);
        }
        $dbh = null;
        /*
                } catch (PDOException $e) {
                    error_log("Fout bij het ophalen van gebruikers: " . $e->getMessage());
                    throw new Exception("Er is een fout opgetreden bij het ophalen van gebruikers.");
                }
        */

        return $lijst;
    }

    public function addGebruiker(
        $emailadres,
        $paswoord,
        $voornaam,
        $familienaam,
        $straat,
        $huisNummer,
        $bus,
        $plaats,
        $postcode,
        $facturatieStraat,
        $facturatieHuisNummer,
        $facturatieBus,
        $facturatiePlaats,
        $facturatiePostcode,
        $checkFacturatie
    ) {

        $options = ['cost' => 12];
        $hashedPassword = password_hash($paswoord, PASSWORD_BCRYPT, $options);

        try {

            $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);

            // gebruikersaccounts --------------------------------------------------------------------

            $sql = "INSERT INTO gebruikersaccounts 
        (emailadres, 
        paswoord,
        disabled)  
        VALUES 
        (:emailadres, 
        :paswoord,
        :disabled
        )";

            $stmt = $dbh->prepare($sql);

            $stmt->execute(
                array(
                    ':emailadres' => $emailadres,
                    ':paswoord' => $hashedPassword,
                    ':disabled' => 0
                )
            );
            $gebruikersAccountId = $dbh->lastInsertId();

            // adressen ----------------------------------------------------------------------------

            $sql = "INSERT INTO adressen 
        (straat,
        huisNummer,
        bus,
        plaatsId,
        actief)
        VALUES
        (:straat,
        :huisNummer,
        :bus,
        :plaatsId,
        :actief)";

            $adresDAO = new AdresDAO();

            // controle checkbox leveringsadres == facturatieadres

            if (
                $checkFacturatie ||
                ($straat === $facturatieStraat &&
                    $huisNummer === $facturatieHuisNummer &&
                    $bus === $facturatieBus &&
                    $plaats === $facturatiePlaats &&
                    $postcode === $facturatiePostcode)
            ) {

                // leveringsadres == facturatieadres -------------------

                $controleAdres = $adresDAO->controleerAdres($straat, $huisNummer, $bus, $postcode, $plaats);

                if (!$controleAdres) {

                    $plaatsId = $adresDAO->getPlaatsIdByPostcode($postcode);

                    $stmt = $dbh->prepare($sql);

                    $stmt->execute(
                        array(
                            ':straat' => $straat,
                            ':huisNummer' => $huisNummer,
                            ':bus' => $bus,
                            ':plaatsId' => $plaatsId,
                            ':actief' => 1
                        )
                    );
                    $leveringsAdresId = $dbh->lastInsertId();
                    $facturatieAdresId = $dbh->lastInsertId();
                } else {
                    $leveringsAdresId = $controleAdres;
                    $facturatieAdresId = $controleAdres;
                }
            } else {
                // leveringsAdres != facturatieAdres -------------------

                // leveringsAdres apart

                $controleAdres = $adresDAO->controleerAdres($straat, $huisNummer, $bus, $postcode, $plaats);

                if (!$controleAdres) {

                    $stmt = $dbh->prepare($sql);

                    $plaatsId = $adresDAO->getPlaatsIdByPostcode($postcode);

                    $stmt->execute(
                        array(
                            ':straat' => $straat,
                            ':huisNummer' => $huisNummer,
                            ':bus' => $bus,
                            ':plaatsId' => $plaatsId,
                            ':actief' => 1
                        )
                    );
                    $leveringsAdresId = $dbh->lastInsertId();
                } else {
                    $leveringsAdresId = $controleAdres;
                }

                // facturatieAdres apart

                $controleAdres = $adresDAO->controleerAdres($facturatieStraat, $facturatieHuisNummer, $facturatieBus, $facturatiePostcode, $facturatiePlaats);

                if (!$controleAdres) {

                    $stmt = $dbh->prepare($sql);

                    $plaatsId = $adresDAO->getPlaatsIdByPostcode($facturatiePostcode);

                    $stmt->execute(
                        array(
                            ':straat' => $facturatieStraat,
                            ':huisNummer' => $facturatieHuisNummer,
                            ':bus' => $facturatieBus,
                            ':plaatsId' => $plaatsId,
                            ':actief' => 1
                        )
                    );
                    $facturatieAdresId = $dbh->lastInsertId();
                } else {
                    $facturatieAdresId = $controleAdres;
                }
            }

            // klanten -----------------------------------------------------

            $sql = "INSERT INTO klanten
        (facturatieAdresId,
        leveringsAdresId)
        VALUES
        (:facturatieAdresId,
        :leveringsAdresId)";

            $stmt = $dbh->prepare($sql);

            $stmt->execute(
                array(
                    ':facturatieAdresId' => $facturatieAdresId,
                    ':leveringsAdresId' => $leveringsAdresId,
                )
            );
            $klantId = $dbh->lastInsertId();

            // natuurlijkepersonen -----------------------------------------

            $sql = "INSERT INTO natuurlijkepersonen
        (klantId,
        voornaam,
        familienaam,
        gebruikersAccountId)
        VALUES
        (:klantId,
        :voornaam,
        :familienaam,
        :gebruikersAccountId)";

            $stmt = $dbh->prepare($sql);

            $stmt->execute(
                array(
                    ':klantId' => $klantId,
                    ':voornaam' => $voornaam,
                    ':familienaam' => $familienaam,
                    ':gebruikersAccountId' => $gebruikersAccountId,
                )
            );

            // rechtspersonen

            // $sql = "INSERT INTO rechtspersonen 
            // (klantId,
            // naam,
            // btwNummer)
            // VALUES
            // (:klantId,
            // :naam,
            // :btwNummer)";

            // $stmt = $dbh->prepare($sql);

            // $stmt->execute(
            //     array(
            //         ':klantId' => $klantId,
            //         ':naam' => $naam,
            //         ':btwNummer' => $btwNummer
            //     )
            // );

            // contactpersonen???

            $dbh = null;
        } catch (PDOException $e) {
            error_log("Fout bij het toevoegen van een nieuwe gebruiker: " . $e->getMessage());
            throw new Exception("Er is een fout opgetreden bij het registreren van een nieuwe gebruiker.");
        }
    }


    // =====================================================================

    public function validateGebruiker(string $gebruiker_emailadres, string $gebruiker_paswoord): ?Gebruiker
    {
        $lijst_gebruikers = $this->getAll();

        foreach ($lijst_gebruikers as $gebruiker) {
            if ($gebruiker->getEmailadres() == $gebruiker_emailadres && $gebruiker->getPaswoord() == $gebruiker_paswoord) {
                return $gebruiker;
            }
        }

        return null;
    }

    public function wijzigWachtwoord(int $gebruikersAccountId, string $nieuw_wachtwoord)
    {
        $options = ['cost' => 12];
        $hashedPassword = password_hash($nieuw_wachtwoord, PASSWORD_BCRYPT, $options);
        $sql = "update gebruikersaccounts set paswoord = :paswoord where gebruikersAccountId = :gebruikersAccountId";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':paswoord' => $hashedPassword, ':gebruikersAccountId' => $gebruikersAccountId));
        $dbh = null;
    }

    public function wijzigFactuurAdres(int $klantId, int $facturatieAdresId)
    {
        $sql = "update klanten set facturatieAdresId = :facturatieAdresId where klantId = :klantId";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':facturatieAdresId' => $facturatieAdresId, ':klantId' => $klantId));
        $dbh = null;
    }

    public function wijzigLeverAdres(int $klantId, int $leverAdresId)
    {
        $sql = "update klanten set leveringsAdresId = :leveringsAdresId where klantId = :klantId";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':leveringsAdresId' => $leverAdresId, ':klantId' => $klantId));
        $dbh = null;
    }

    public function wijzigBedrijfsGegevens(int $klantId, $bedrijfsnaam, $btwnummer)
    {
        //controleer als klantId al bestaat in de database
        $sql = "select * from rechtspersonen where klantId = :klantId";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':klantId' => $klantId));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbh = null;

        if ($result) {
            //als resultaat bestaat, wijzig de gegevens
            $sql = "
            update rechtspersonen set naam = :naam, 
            btwNummer = :btwNummer
            where klantId = :klantId
            ";
            $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(':naam' => $bedrijfsnaam, ':btwNummer' => $btwnummer, ':klantId' => $klantId));
            $dbh = null;
        } else {
            $sql = "insert into rechtspersonen (klantId, naam, btwNummer)  
            VALUES (:klantId, :naam, :btwNummer)";
            $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(':klantId' => $klantId, ':naam' => $bedrijfsnaam, ':btwNummer' => $btwnummer, ));
            $dbh = null;
        }
    }

    public function getDeFavorieteCategorieVanEenKlant(int $klantId): ?array {
        $sql = "WITH ProductPurchaseCounts AS (
            SELECT
                bc.artikelId,
                ac.categorieId,
                SUM(bc.aantalBesteld - bc.aantalGeannuleerd) AS totalPurchases
            FROM Bestellijnen bc
            JOIN Artikelen a ON bc.artikelId = a.artikelId
            JOIN ArtikelCategorieen ac ON a.artikelId = ac.artikelId
            JOIN Bestellingen b ON bc.bestelId = b.bestelId
            WHERE b.klantId = :klantId
            GROUP BY bc.artikelId, ac.categorieId
        ),
        RankedCategories AS (
            SELECT
                artikelId,
                categorieId,
                totalPurchases,
                RANK() OVER (PARTITION BY artikelId ORDER BY totalPurchases DESC) AS categoryRank
            FROM ProductPurchaseCounts
        )
        SELECT
            r.categorieId
        FROM RankedCategories r
        JOIN Categorieen c ON r.categorieId = c.categorieId
        WHERE r.categoryRank = 1
        
        order by totalPurchases desc
        limit 1";
        $dbh  = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':klantId' => $klantId));
        $dbh = null;
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $favorieteCategorie = null;

        if ($result) {
            $favorieteCategorie = $result;
        }

        return $favorieteCategorie;
    }

    public function blockGebruiker(int $gebruikersAccountId)
    {
        $sql = "update gebruikersaccounts set disabled = :disabled where gebruikersAccountId = :gebruikersAccountId";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':disabled' => 1, ':gebruikersAccountId' => $gebruikersAccountId));
        $dbh = null;
    }
}
