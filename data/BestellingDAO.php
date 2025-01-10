<?php
declare(strict_types=1);
require_once('data/autoloader.php');

class BestellingDAO
{
    public function addBestelBon(Bestelling $bestelling): int
    {

        $sql = "insert into bestellingen (besteldatum, klantId, betaald, betalingscode, betaalwijzeId, annulatie, annulatiedatum, terugbetalingscode, bestellingsStatusId, actiecodeGebruikt, bedrijfsnaam, btwNummer, voornaam, familienaam, facturatieAdresId, leveringsAdresId) 
                                  values (:besteldatum, :klantId, :betaald, :betalingscode, :betaalwijzeId, :annulatie, :annulatiedatum, :terugbetalingscode, :bestellingsStatusId, :actiecodeGebruikt, :bedrijfsnaam, :btwNummer, :voornaam, :familienaam, :facturatieAdresId, :leveringsAdresId)";

        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(
            ':besteldatum' => $bestelling->getBestelDatum(),
            ':klantId' => $bestelling->getKlantId(),
            ':betaald' => $bestelling->getBetaald(),
            ':betalingscode' => $bestelling->getBetalingsCode(),
            ':betaalwijzeId' => $bestelling->getBetaalwijzeId(),
            ':annulatie' => $bestelling->getAnnulatie(),
            ':annulatiedatum' => $bestelling->getAnnulatieDatum(),
            ':terugbetalingscode' => $bestelling->getTerugbetalingsCode(),
            ':bestellingsStatusId' => $bestelling->getBestellingsStatusId(),
            ':actiecodeGebruikt' => $bestelling->getActiecodeGebruikt(),
            ':bedrijfsnaam' => $bestelling->getBedrijfsnaam(),
            ':btwNummer' => $bestelling->getBtwNummer(),
            ':voornaam' => $bestelling->getVoornaam(),
            ':familienaam' => $bestelling->getFamilienaam(),
            ':facturatieAdresId' => $bestelling->getFacturatieAdresId(),
            ':leveringsAdresId' => $bestelling->getLeveringsAdresId()));
        $bestelbonId = intval($dbh->lastInsertId());
        $dbh = null;

        return $bestelbonId;
    }

    public function addBestelLijn(int $bestelId, int $artikelId, int $aantalBesteld, int $aantalGeannuleerd)
    {
        //Voeg een bestellijn toe, deze functie word meermaals gecalled als er meerdere artikelen in 1 bestelling zitten
        $sql = "insert into bestellijnen (bestelId, artikelId, aantalBesteld, aantalGeannuleerd) values (:bestelId, :artikelId, :aantalBesteld, :aantalGeannuleerd)";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':bestelId' => $bestelId, ':artikelId' => $artikelId, ':aantalBesteld' => $aantalBesteld, ':aantalGeannuleerd' => $aantalGeannuleerd));
        $dbh = null;

        //Verminder de voorraad van het bestelde artikel met het aantal dat besteld is
        $this->verminderVoorraadArtikel($artikelId, $aantalBesteld);

    }

    public function verminderVoorraadArtikel(int $artikelId, int $aantal)
    {
        //als resultaat bestaat, wijzig de gegevens
        $sql = "update artikelen set voorraad = voorraad - :aantal where artikelId = :artikelId";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':aantal' => $aantal, ':artikelId' => $artikelId));
        $dbh = null;
    }

    public function getBestellingenByKlantId(int $klantId)
    {
        $sql = "select * from bestellingen where klantId = :klantId order by besteldatum desc";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':klantId' => $klantId));
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $list = array();
        foreach ($resultSet as $rij) {
            $bestellijnen = $this->getBestellijnenByBestelId(intval($rij['bestelId']));

            $bestelling = new Bestelling((int) $rij['bestelId'], $rij['besteldatum'], (int) $rij['klantId'], (int) $rij['betaald'], (string) $rij['betalingscode'], (int) $rij['betaalwijzeId'], (int) $rij['annulatie'], $rij['annulatiedatum'], (string) $rij['terugbetalingscode'], (int) $rij['bestellingsStatusId'], (int) $rij['actiecodeGebruikt'], (string) $rij['bedrijfsnaam'], (string) $rij['btwNummer'], (string) $rij['voornaam'], (string) $rij['familienaam'], (int) $rij['facturatieAdresId'], (int) $rij['leveringsAdresId'], $bestellijnen);
            array_push($list, $bestelling);
        }
        $dbh = null;

        return $list;
    }

    public function getBestellijnenByBestelId(int $bestelId)
    {
        $sql = "select * from bestellijnen where bestelId = :bestelId";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':bestelId' => $bestelId));
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $list = array();
        foreach ($resultSet as $rij) {
            $bestellijn = new Bestellijn((int) $rij['bestellijnId'], (int) $rij['bestelId'], (int) $rij['artikelId'], (int) $rij['aantalBesteld'], (int) $rij['aantalGeannuleerd']);
            array_push($list, $bestellijn);
        }
        $dbh = null;

        return $list;
    }

    public function getBetaalwijzeById(int $betaalwijzeId): string
    {
        $sql = "select naam from betaalwijzes where betaalwijzeId = :betaalwijzeId";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':betaalwijzeId' => $betaalwijzeId));
        $resultSet = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultSet) {
            return $resultSet['naam'];
        } else {
            return 'onbekend';
        }

    }

    public function getBestellingsStatusById(int $bestellingsStatusId): string
    {
        $sql = "select naam from bestellingsstatussen where bestellingsStatusId = :bestellingsStatusId";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':bestellingsStatusId' => $bestellingsStatusId));
        $resultSet = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultSet) {
            return $resultSet['naam'];
        } else {
            return 'onbekend';
        }

    }

    public function annuleerBestelling(int $bestelId)
    {
        //Tel de voorraad van geannuleerde bestellingen terug op
        $sql = "select bestellijnen.bestelId, bestellijnen.artikelId, bestellijnen.aantalBesteld from bestellijnen where bestellijnen.bestelId = :bestelId";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':bestelId' => $bestelId));
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($resultSet as $rij) {
            $this->addVoorraad($rij['artikelId'], $rij['aantalBesteld']);
        }

        //annuleer bestelbon
        $sql = "update bestellingen set annulatie = :annulatie, annulatiedatum = :annulatiedatum, bestellingsStatusId = :bestellingsStatusId where bestelId = :bestelId";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':annulatie' => 1, ':annulatiedatum' => date("Y/m/d H:i:s"), ':bestellingsStatusId' => 3, ':bestelId' => $bestelId));
        $dbh = null;

        //annuleer elke bestellijn van deze bestelling
        $sql = "update bestellijnen set aantalGeannuleerd = aantalBesteld where bestelId = :bestelId";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':bestelId' => $bestelId));
        $dbh = null;
    }

    public function addVoorraad(int $artikelId, int $aantal)
    {
        $sql = "update artikelen set voorraad = voorraad + :aantal where artikelId = :artikelId";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':aantal' => $aantal, ':artikelId' => $artikelId));
        $dbh = null;
    }

    public function annuleerLosseStuks(int $bestellijnId, int $aantal)
    {
        //annuleer aantal stuks van gegeven bestellijnId
        $sql = "update bestellijnen set aantalGeannuleerd = aantalGeannuleerd + :aantalGeannuleerd where bestellijnId = :bestellijnId";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':aantalGeannuleerd' => $aantal, ':bestellijnId' => $bestellijnId));
        $dbh = null;

        //zoek het artikelId dat bij deze bestellijn hoort
        $sql = "select artikelId from bestellijnen where bestellijnId = :bestellijnId";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':bestellijnId' => $bestellijnId));
        $resultSet = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbh = null;

        //Voeg voorraad toe van dit artikel gelijk aan het aantal dat geannuleerd is
        $this->addVoorraad(intval($resultSet['artikelId']), $aantal);
    }

}