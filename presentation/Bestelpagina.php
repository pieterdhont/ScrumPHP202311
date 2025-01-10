<?php

declare(strict_types=1);

?>

<!DOCTYPE html>
<html lang="nl">

<head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/bestelPagina.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src="JavaScript/bestelpagina.js" defer></script>
        <title>Prularia: Bestelpagina</title>
        <link rel="icon" href="./assets/prulariaicon.ico">
</head>

<body>
        <!-- Invoegen header -->
        <?php include("includes/header.php"); ?>

        <div class="wrapper">
                <p id="feedback"></p>
                <!-- Overzicht van winkelmand met de nodige informatie over bestelling -->
                <h1>Besteloverzicht</h1>
                <div class="orderOverzicht">
                        <?php
                        foreach ($orderCookie as $orderLine) {
                                if (file_exists('assets/thumbnailArtikels/' . $orderLine->id . '.png')) {
                                        $thumbnail_locatie = 'assets/thumbnailArtikels/' . $orderLine->id . '.png';
                                } else {
                                        $thumbnail_locatie = 'assets/thumbnailArtikels/artikelVoorbeeldImg.png';
                                }
                                echo
                                        '<div class="artikel-info" data-order_object=' . str_replace(' ', '_', json_encode($orderLine)) . '">' .
                                        '<div class="artikelImg-container">' .
                                        '<img class="thumbnail" src="' . $thumbnail_locatie . '">' .
                                        '</div>' .
                                        '<div class="bestellijnInfo-container">' .
                                        '<p class="artikel-naam">' . $orderLine->naam . '</p>' .
                                        '<p><span>Aantal:</span><input data-voorraad="' . $orderLine->artikelVoorraad . '" class="bestel_aantal" oninput="checkInput(this)" type="number" min="0" max="99" required value="' . $orderLine->aantal . '"></input> Stuk(s)</p>' .
                                        '<p><span>Prijs per stuk:</span><strong>€' . number_format(floatval($orderLine->prijs), 2) . '</strong></p>' .
                                        '</div>' .
                                        '</div>';
                        }
                        ?>

                        <div class="betaalOvezicht">
                                <?php
                                echo '<p class="' . $totaalClass . '" id="totaal">Totaal: €' . number_format($totaalBedrag, 2) . '</p>';
                                if ($korting !== 0) {
                                        echo '<p class="afrekenenTekst metKorting" id="korting">Korting: <span>' . $korting . '%</span></p>';
                                        echo '<p class="afrekenenTekst" id="teBetalen">Te betalen: €' . number_format($teBetalen, 2) . '</p>';
                                }
                                ?>
                                <form method="post" action="bestellingDoorgeven.php">
                                        <label for="actiecode"><span>Actiecode: </span>
                                                <input id="actiecode" name="actiecode" type=text minlength="1"
                                                        maxlength="45" placeholder="Actiecode.."
                                                        value="<?php if (isset($actiecode)) {
                                                                echo $actiecode;
                                                        } ?>">
                                        </label>
                                        <input type="submit" value="Toepassen">
                                </form>
                        </div>
                </div>
                <p class="goedeFeedback">
                        <?php if ($actiecodeFeedback !== '') {
                                echo $actiecodeFeedback;
                        } ?>
                </p>
                <p class="fout">
                        <?php if ($actiecodeFout !== '') {
                                echo $actiecodeFout;
                        } ?>
                </p>
                <button class="button" id="update-caddy">Update winkelmand</button>

                <h1>Bestelgegevens</h1>
                <div class="bestsllerInfo-container">
                        <form method="post" action="bestellingDoorgeven.php?action=bevestig">
                                <div class="bestellerInfo" id="klantSoort">
                                        <h2>Soort klant</h2>
                                        <input type="radio" id="particulier" name="soortklant" value="Particulier"
                                                checked>
                                        <label for="particulier">Particulier</label>
                                        <input type="radio" id="zakelijk" name="soortklant" value="Zakelijk">
                                        <label for="zakelijk">Zakelijk</label>
                                </div>

                                <div class="bestellerInfo" id="klantgegevens">
                                        <h2>Klantgegevens</h2>
                                        <label> <span>Voornaam:</span>
                                                <input required id="klant_voornaam" name="klant_voornaam" type="text"
                                                        value="<?php echo $gebruiker->getVoornaam() ?>">
                                        </label>
                                        <label><span>Familienaam:</span>
                                                <input required id="klant_familienaam" name="klant_familienaam"
                                                        type="text" value="<?php echo $gebruiker->getFamilienaam() ?>">
                                        </label>
                                        <label style="display: none;" id="label_bedrijfsnaam"><span>Bedrijfsnaam:</span>
                                                <input id="klant_bedrijfsnaam" name="klant_bedrijfsnaam" type="text"
                                                        value="<?php echo $gebruiker->getBedrijfsnaam() ?>">
                                        </label>
                                        <label style="display: none;" id="label_BTWNummer"><span>BTW Nummer:</span>
                                                <input id="klant_BTWNummer" name="klant_BTWNummer" type="text"
                                                        value="<?php echo $gebruiker->getBTWNummer() ?>">
                                        </label>
                                </div>

                                <!--Een onzichtbaar input veld voor de actiecode mee te geven bij het verzenden van de bestelling-->
                                <input type="text" style="display: none;" name="actiecode" value="<?php if (isset($_POST['actiecode'])) {
                                        echo $_POST['actiecode'];
                                } ?>">

                                <div class="bestellerInfo" id="facturatieadres_container">
                                        <h2>Facturatieadres</h2>
                                        <label for="factuur_straat"><span>Straat:</span>
                                                <input required id="factuur_straat" name="factuur_straat" type="text"
                                                        value="<?php echo $gebruiker->getFacturatieAdres()->getStraat() ?>">
                                        </label>
                                        <label for="factuur_huisnummer"><span>Huisnummer:</span>
                                                <input required id="factuur_huisnummer" name="factuur_huisnummer"
                                                        type="text"
                                                        value="<?php echo $gebruiker->getFacturatieAdres()->getHuisnummer() ?>">
                                        </label>
                                        <label for="factuur_bus"><span>Bus:</span>
                                                <input id="factuur_bus" name="factuur_bus" type="text"
                                                        value="<?php echo $gebruiker->getFacturatieAdres()->getBus() ?>">
                                        </label>
                                        <label for="factuur_postcode"><span>Postcode:</span>
                                                <input required id="factuur_postcode" name="factuur_postcode"
                                                        type="text"
                                                        value="<?php echo $gebruiker->getFacturatieAdres()->getPostcode() ?>">
                                        </label>
                                        <label for="factuur_plaats"><span>Plaats:</span>
                                                <input required id="factuur_plaats" name="factuur_plaats" type="text"
                                                        value="<?php echo $gebruiker->getFacturatieAdres()->getPlaats() ?>">
                                        </label><br>

                                        <label for="gelijkadres">
                                                <input type="checkbox" id="gelijkadres" name="gelijkadres" checked />
                                                Leveradres is hetzelfde als factuuradres
                                        </label>
                                </div>



                                <div class="bestellerInfo" id="leveradres_container" style="display:none;">
                                        <h2>Leveradres: </h2>
                                        <label for="lever_straat"><span>Straat:</span>
                                                <input required id="lever_straat" name="lever_straat" type="text"
                                                        value="<?php echo $gebruiker->getLeveringsAdres()->getStraat() ?>">
                                        </label>
                                        <label for="lever_huisnummer"><span>Huisnummer:</span>
                                                <input required id="lever_huisnummer" name="lever_huisnummer"
                                                        type="text"
                                                        value="<?php echo $gebruiker->getLeveringsAdres()->getHuisnummer() ?>">
                                        </label>
                                        <label for="lever_bus"><span>Bus:</span>
                                                <input id="lever_bus" name="lever_bus" type="text"
                                                        value="<?php echo $gebruiker->getLeveringsAdres()->getBus() ?>">
                                        </label>
                                        <label for="lever_postcode"><span>Postcode:</span>
                                                <input required id="lever_postcode" name="lever_postcode" type="text"
                                                        value="<?php echo $gebruiker->getLeveringsAdres()->getPostcode() ?>">
                                        </label>
                                        <label for="lever_plaats"><span>Plaats:</span>
                                                <input required id="lever_plaats" name="lever_plaats" type="text"
                                                        value="<?php echo $gebruiker->getLeveringsAdres()->getPlaats() ?>">
                                        </label>
                                </div>

                                <div class="bestellerInfo" id="betaalmethode">
                                        <h2>Betaalmethode: </h2>
                                        <label>
                                                <input type="radio" id="krediet" name="betaalmethode" value="1" checked>
                                                <img class="betaalmethode" src="assets/visa.png" alt="krediet"
                                                        title="kredietkaart Visa/Mastercard">
                                        </label>
                                        <label>
                                                <input type="radio" id="overschrijving" name="betaalmethode" value="2">
                                                <img class="betaalmethode" src="assets/bank-transfer.png"
                                                        alt="overschrijving" title="overschrijving">
                                        </label> <br>
                                </div>
                                <input type="submit" value="Bestellen">
                        </form>
                </div>
                <?php include 'includes/cookies.php'; ?>
        </div>
        <?php include 'includes/footer.php'; ?>
</body>

</html>