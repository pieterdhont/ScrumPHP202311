<?php

declare(strict_types=1);
require_once('data/autoloader.php');

class AdresDAO
{
    public function getAll(): array
    {
        $sql = "
        SELECT
        adressen.adresId,
        adressen.straat,
        adressen.huisNummer,
        adressen.bus,
        adressen.plaatsId,
        adressen.actief,
        plaatsen.postcode,
        plaatsen.plaats
        FROM adressen
        LEFT JOIN plaatsen
        ON adressen.plaatsId = plaatsen.plaatsId
        ";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSet = $dbh->query($sql);
        $lijst = array();
        foreach ($resultSet as $rij) {
            $adres = new Adres(
                (int)$rij['adresId'],
                (string)$rij['straat'],
                (string)$rij['huisNummer'],
                (string)$rij['bus'],
                (int)$rij['plaatsId'],
                (int)$rij['actief'],
                (string)$rij['postcode'],
                (string)$rij['plaats']
            );
            array_push($lijst, $adres);
        }
        $dbh = null;

        return $lijst;
    }

    public function addAdres($straat, $huisNummer, $bus, $postcode, $plaats) : bool
    {
        // Add adres to database, without ID because it's auto increment

        //Kijk als het plaatsId bestaat in de database
        $plaatsId = $this->getPlaatsIdByPostcode($postcode);

        //Als het plaatsId gevonden is
        if ($plaatsId !== 0) {
            $sql = "insert into adressen (straat, huisNummer, bus, plaatsId, actief) values (:straat, :huisNummer, :bus, :plaatsId, :actief)";
            $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(':straat' => $straat, ':huisNummer' => $huisNummer, ':bus' => $bus, ':plaatsId' => $plaatsId, ':actief' => 1));
            $dbh = null;
            return true;
        } else {
            return false;
        }
    }

    public function getAdresById(int $adresId): ?Adres
    {
        $sql = "
        SELECT
        adressen.adresId,
        adressen.straat,
        adressen.huisNummer,
        adressen.bus,
        adressen.plaatsId,
        adressen.actief,
        plaatsen.postcode,
        plaatsen.plaats
        FROM adressen
        LEFT JOIN plaatsen
        ON adressen.plaatsId = plaatsen.plaatsId
        WHERE adresId =" . $adresId;
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSet = $dbh->query($sql);
        $adres = null;
        foreach ($resultSet as $rij) {
            $adres = new Adres(
                (int)$rij['adresId'],
                (string)$rij['straat'],
                (string)$rij['huisNummer'],
                (string)$rij['bus'],
                (int)$rij['plaatsId'],
                (int)$rij['actief'],
                (string)$rij['postcode'],
                (string)$rij['plaats']
            );
        }
        $dbh = null;

        if ($adres !== null) {
            return $adres;
        } else {
            $leegAdres = new Adres(0, '', '', '', 0, 0, '', '');
            return $leegAdres;
        }
    }
    public function getPlaatsIdByPostcode(string $postcode): int
    {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);

        $sql = '
        SELECT
        plaatsId
        FROM plaatsen
        WHERE postcode = "' . $postcode . '"';

        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        $resultaat = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbh = null;

        if ($resultaat) {
            return $resultaat['plaatsId'];
        } else {
            return 0;
        }
    }

    public function controleerAdres(string $straat, string $huisNummer, string $bus, string $postcode, string $plaats): int
    {

        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);

        $sql = 'select adressen.adresId from adressen
        left join plaatsen
        on adressen.plaatsId = plaatsen.plaatsId
        where 
        adressen.straat = "' . $straat .
            '" and adressen.huisNummer = "' . $huisNummer .
            '" and adressen.bus = "' . $bus .
            '" and plaatsen.postcode = "' . $postcode .
            '" and plaatsen.plaats = "' . $plaats . '"';

        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        $resultaat = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbh = null;

        if ($resultaat) {
            return $resultaat['adresId'];
        } else {
            return 0;
        }
    }

    public function controleerPostcode(string $postcode): bool
    {

        $sql = "SELECT * FROM plaatsen WHERE postcode = :postcode";

        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':postcode', $postcode);
        $stmt->execute();

        $resultaat = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbh = null;

        return ($resultaat !== false);
    }

}
