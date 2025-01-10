<?php
declare(strict_types=1);
require_once('data/autoloader.php');

class ActiecodeDAO
{
    public function getActieCodeByNaam(string $actiecodeNaam)
    {

        $sql = "select actiecodeId, naam, geldigVanDatum, geldigTotDatum, isEenmalig from actiecodes where naam = " . '"' . $actiecodeNaam . '"';
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbh = null;

        if ($result) {
            $actiecode = new Actiecode((int) $result['actiecodeId'], (string) strtolower($result['naam']), (string) $result['geldigVanDatum'], (string) $result['geldigTotDatum'], (int) $result['isEenmalig']);
            return $actiecode;
        }


    }

    public function verwijderActiecode(Actiecode $actiecode) {
        $sql = "delete FROM actiecodes WHERE actiecodeId=" . $actiecode->getActiecodeId();
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
    }

}
