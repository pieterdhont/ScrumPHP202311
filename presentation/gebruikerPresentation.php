<?php
//inlogformulier.php
declare(strict_types=1); ?>

<!DOCTYPE HTML>
<html lang="nl">
<html>

<head>
    <meta charset=utf-8>
    <title>Prularia Gebruiker</title>
    <link rel="stylesheet" href="css/gebruikerPagina.css">
    <script src="./JavaScript/gebruikerPagina.js" defer></script>
    <link rel="icon" href="./assets/prulariaicon.ico">
</head>

<body>
    <?php include 'includes/header.php' ?>

    <nav id="gebruikersprofiel-tabs">
        <a id="link-accountoverzicht" class="actief" href="#">Accountoverzicht</a>
        <a id="link-bestelgeschiedenis" href="#">Bestelgeschiedenis</a>
        <a id="link-mijnreviews" href="#">Mijn reviews</a>
        <a style="color: red;" id="link-uitloggen" href="gebruiker.php?action=loguit">Uitloggen</a>
        <link rel="icon" href="./assets/prulariaicon.ico">
    </nav>


    <div class="wrapper">




        <p id="feedback"></p>



        <div id="Accountoverzicht-container">



            <h1 class="titelh1">Accountoverzicht</h1>


            <div>
                <h2 class="titelh2">Facturatieadres wijzigen</h2>
                <form method="post" id="wijzig-facturatieadres" action="gebruiker.php?action=wijzigfactuuradres">
                    <h3>Geef je facturatiegegevens in</h3>
                    <label for="factuur_straat"><span>Straat</span></label>
                    <input required id="factuur_straat" name="factuur_straat" type="text"
                        value="<?php echo $gebruiker->getFacturatieAdres()->getStraat() ?>">
                    <br>
                    <label for="factuur_huisnummer"><span>Huisnummer</span></label>
                    <input required id="factuur_huisnummer" name="factuur_huisnummer" type="text"
                        value="<?php echo $gebruiker->getFacturatieAdres()->getHuisnummer() ?>">

                    <br>
                    <label for="factuur_bus"><span>Bus</span></label>
                    <input id="factuur_bus" name="factuur_bus" type="text"
                        value="<?php echo $gebruiker->getFacturatieAdres()->getBus() ?>">

                    <br>
                    <label for="factuur_postcode"><span>Postcode</span></label>
                    <input required id="factuur_postcode" name="factuur_postcode" type="text"
                        value="<?php echo $gebruiker->getFacturatieAdres()->getPostcode() ?>">

                    <br>
                    <label for="factuur_plaats"><span>Plaats</span></label>
                    <input required id="factuur_plaats" name="factuur_plaats" type="text"
                        value="<?php echo $gebruiker->getFacturatieAdres()->getPlaats() ?>">

                    <br>
                    <div class="button-container">
                        <input class="button" type="submit" value="Opslaan">
                    </div>
                </form>
            </div>






            <div>
                <h2 class="titelh2">Leveradres wijzigen</h2>
                <form method="post" id="wijzig-leveradres" action="gebruiker.php?action=wijzigleveradres">
                    <h3>Geef je leveringsadres gegevens in</h3>
                    <label for="lever_straat"><span>Straat</span></label>
                    <input required id="lever_straat" name="lever_straat" type="text"
                        value="<?php echo $gebruiker->getLeveringsAdres()->getStraat() ?>">

                    <br>
                    <label for="lever_huisnummer"><span>Huisnummer</span></label>
                    <input required id="lever_huisnummer" name="lever_huisnummer" type="text"
                        value="<?php echo $gebruiker->getLeveringsAdres()->getHuisnummer() ?>">

                    <br>
                    <label for="lever_bus"><span>Bus</span></label>
                    <input id="lever_bus" name="lever_bus" type="text"
                        value="<?php echo $gebruiker->getLeveringsAdres()->getBus() ?>">

                    <br>
                    <label for="lever_postcode"><span>Postcode</span></label>
                    <input required id="lever_postcode" name="lever_postcode" type="text"
                        value="<?php echo $gebruiker->getLeveringsAdres()->getPostcode() ?>">

                    <br>
                    <label for="lever_plaats"><span>Plaats</span></label>
                    <input required id="lever_plaats" name="lever_plaats" type="text"
                        value="<?php echo $gebruiker->getLeveringsAdres()->getPlaats() ?>">

                    <br>
                    <div class="button-container">
                        <input class="button" type="submit" value="Opslaan">
                    </div>
                </form>
            </div>





            <div>

                <h2 class="titelh2">Bedrijfsgegevens wijzigen</h2>


                <form method="post" id="wijzig-bedrijfsgegevens" action="gebruiker.php?action=wijzigbedrijfgegevens">
                    <h3>Geef je bedrijfsgegevens in</h3>
                    <label id="label_bedrijfsnaam"><span>Bedrijfsnaam</span></label>
                    <input id="klant_bedrijfsnaam" name="klant_bedrijfsnaam" type="text" placeholder="Bedrijfsnaam.."
                        value="<?php echo $gebruiker->getBedrijfsnaam() ?>">

                    <br>
                    <label id="label_BTWNummer"><span>BTW Nummer</span></label>
                    <input id="klant_BTWNummer" name="klant_BTWNummer" type="text" placeholder="BTW Nummer.."
                        value="<?php echo $gebruiker->getBTWNummer() ?>">

                    <br>
                    <div class="button-container">
                        <input class="button" type="submit" value="Opslaan">
                    </div>
                </form>
            </div>








            <div>
                <h2 class="titelh2">Wachtwoord wijzigen</h2>
                <form method="post" id="wijzig-paswoord" action="gebruiker.php?action=wijzigpaswoord">

                    <h3>Geef een nieuw wachtwoord in om dit in te stellen</h3>
                    <label for="paswoord">Wachtwoord</label>
                    <input type="password" id="paswoord" name="paswoord" placeholder="Je wachtwoord.." required
                        maxlength="50" minlength="8">

                    <br>

                    <label for="reg_paswoord2">Herhaal wachtwoord</label>
                    <input type="password" id="paswoord2" name="paswoord2" placeholder="Je wachtwoord.." required
                        maxlength="50" minlength="8">

                    <br>

                    <div class="button-container">
                        <input class="button" type="submit" value="Opslaan">
                    </div>
                </form>
            </div>

            <div class="account-blokkeren-container">
                <a id="account-blokkeren" style="color: red;" href="#" data-userid="<?php echo $gebruiker->getGebruikersAccountId(); ?>">Account deactiveren</a>
            </div>
        </div>
        


        <div id="Bestelgeschiedenis-container" style="display:none;">
            <h1>Bestelgeschiedenis</h1>

            <?php
            //voor elke bestelling van de klant
            foreach ($gebruiker_bestelbonnen as $bestelling) {
                echo '<div class="bestelbon">
                        <div class="bestelbon-gegevens">
                            <h2>Bestelnummer <strong>' . $bestelling->getBestelId() . '</strong></h2>
                            <p>Besteldatum <strong>' . $bestelling->getBestelDatum() . '</strong></p>
                            <p>Betalingscode <strong>' . $bestelling->getBetalingscode() . '</strong></p>
                            <p>Betaalwijze <strong>' . $bestellingSvc->getBetaalwijzeById($bestelling->getBetaalwijzeId()) . '</strong></p>
                            <p>Bestellingsstatus <strong>' . $bestellingSvc->getBestellingsStatusById($bestelling->getBestellingsStatusId()) . '</strong></p>';

                if (($bestelling->getAnnulatie() == 0) && ($bestelling->getBestellingsStatusId() < 3)) {
                    echo '<a href="#" class="annuleerBestelling" style="color: red;" data-bestelid=' . $bestelling->getBestelId() . '>Bestelling annuleren</a>';
                } else {
                    echo '<p style="color: red;">' . '</p>';
                }
                echo '<h3>Artikelen bij deze bestelling</h3>';
                //Einde div bestelbon-gegevens
                echo '</div>';


                //voor elke bestellijn uit bestellingen van de klant
                echo '<div class="bestelde-artikels-container">';
                foreach ($bestelling->getBestellijnen() as $bestellijn) {
                    $artikelId = $bestellijn->getArtikelId();
                    $artikel = $artikelSvc->getArtikelById($artikelId);
                    $score = $artikelSvc->getGemiddeldeScorePerArtikel($artikelId);
                    $thumbnailPath = 'assets/thumbnailArtikels/artikelVoorbeeldImg.png';
                    if (file_exists('assets/thumbnailArtikels/' . $artikel->getId() . '.png')) {
                        $thumbnailPath = 'assets/thumbnailArtikels/' . $artikel->getId() . '.png';
                    } ?>
                    <div class="artikelContainer" id="artikel<?php echo $artikel->getId(); ?>"
                        data-id="<?php echo $artikel->getId(); ?>">
                        <div class="fotoContainer">
                            <img src="<?php echo $thumbnailPath; ?>" loading="lazy"
                                alt="<?php echo $artikel->getNaam(); ?> foto" title="<?php echo $artikel->getNaam(); ?>">
                        </div>
                        <div class="artikelinfo">
                            <p class="artikelNaam">
                                <?php echo $artikel->getNaam(); ?>
                            </p>

                            <p class="artikelBesteld">Aantal: 
                                <span class="aantal-besteld">
                                    <?php echo $bestellijn->getAantalBesteld() - $bestellijn->getAantalGeannuleerd(); ?>
                                </span>
                                <span style="color: red;">
                                    <?php if ($bestellijn->getAantalGeannuleerd() > 0) {
                                        echo '('. $bestellijn->getAantalGeannuleerd() . ' geannuleerd)';
                                    }            
                                    ?>
                                </span>
                            
                            </p>
                            <p class="artikelPrijs">&euro;
                                <?php echo number_format((float) $artikel->getPrijs(), 2, '.', ''); ?>
                            </p>
                            <?php
                            if ($artikel->getVoorraad() == 0) { ?>
                                <p class="artikelVoorraad">Niet meer in voorraad</p>
                            <?php } else if ($artikel->getVoorraad() < 5) { ?>
                                    <p class="artikelVoorraad">Nog
                                    <?php echo $artikel->getVoorraad(); ?> stuk(s) beschikbaar!
                                    </p>
                            <?php } ?>
                            <!--------------------------------EINDE GREG--------------------------------->
                    <?php if (is_null($score)) { ?>
                    <div class="score geen">Geen reviews</div>
                    <?php } else { ?>
                    <div class="score" style="--percent: <?php print($score / 5 * 100); ?>%;"
                        title="<?php echo $score ?> van 5 steren">★★★★★</div>
                    <?php } ?>


                    <?php if ($artikelSvc->heeftKlantAlEenReviewGeplaatstVanDitArtikel($gebruiker->getKlantId(), $artikelId)) { ?>
                    <a class="schrijf-review-link"
                        href="artikelInformatie.php?id=<?php echo $artikel->getId(); ?>">Schrijf een
                        review</a>
                    <?php } ?>


                    <?php if ($artikel->getVoorraad() > 0) { ?>
                    <a href="#" class="toevoegenAanWinkelmandKnop"
                        data-voorraad="<?php echo $artikel->getVoorraad(); ?>"
                        data-id="<?php echo $artikel->getId(); ?>" data-prijs="<?php echo $artikel->getPrijs(); ?>"
                        data-naam="<?php echo $artikel->getNaam(); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                            class="bi bi-cart-plus" viewBox="0 0 16 16">
                            <path
                                d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z" />
                            <path
                                d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7h-8.17zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                        </svg>
                    </a>
                    <?php } ?>
                </div>
                <button
                    onclick="window.location.href = 'artikelInformatie.php?id=<?php echo $artikel->getId(); ?>';">Meer
                    info
                </button>
                <?php if ($bestellijn->getAantalBesteld() !== $bestellijn->getAantalGeannuleerd()) { ?>
                <form class="annuleer-stuks-form" method="post" action="gebruiker.php?annuleerartikel=<?php echo $bestellijn->getBestellijnId() ?>">
                    <label for="annuleer-stuks">stuks annuleren</label>
                    <select name="annuleer-stuks">
                    <?php for ($i = 1; $i <= $bestellijn->getAantalBesteld() - $bestellijn->getAantalGeannuleerd(); $i++) { ?>
                        <option value=<?php echo $i; ?>><?php echo $i; ?></option>
                    <?php } ?>
                    </select>
                    <input type="submit" value="annuleren"></input>
                </form>
                <?php } ?>

            </div>
            <?php } ?>
        </div>
    </div>
    <?php } ?>


    </div>



    <div id="Mijnreviews-container" style="display: none;">
        <h1>Mijn reviews</h1>
        <?php
        foreach ($gebruikerSvc->getReviewsByKlantId($gebruiker->getKlantId()) as $review) {
            $review_artikel_id = $review->getArtikelId();
            if (file_exists('assets/thumbnailArtikels/' . $review_artikel_id . '.png')) {
                $thumbnail_locatie = 'assets/thumbnailArtikels/' . $review_artikel_id . '.png';
            } else {
                $thumbnail_locatie = 'assets/thumbnailArtikels/artikelVoorbeeldImg.png';
            }
            echo '<div class="review-container" data-reviewid="' . $review->getId() . '">
                <div class="fotoContainer">
                    <img class="thumbnail" src="' . $thumbnail_locatie . '" loading="lazy" alt="'. $artikel->getNaam() .' foto" title="'.$artikel->getNaam().'">
                </div>
                <p class="artikel-naam">' . $artikelSvc->getArtikelById($review_artikel_id)->getNaam() . '</p>'; ?>
        <div class="score" style="--percent: <?php print($review->getScore() / 5 * 100); ?>%;"
            title="<?php echo $score ?> van 5 steren">★★★★★</div>
        <?php echo '<p class=review-commentaar>' . $review->getCommentaar() . '</p>
                <p class="datum-geplaatst">' . $review->getDatum() . '</p>  
                <div class=bewerk-review-links-container>
                    <a class="bewerk-review-link" data-reviewid="' . $review->getId() . '" data-commentaar="' . $review->getCommentaar() . '" data-score="' . $review->getScore() . '">Bewerken</a>
                    <a class="verwijder-review-link" data-reviewid="' . $review->getId() . '">Verwijderen</a>  
                </div>
                <form class="bewerk-review-form" style="display:none;" action="gebruiker.php?bewerkreview=' . $review->getId() . '" method="post">
                    <div class="sterrenScore">
                        <input class="ster_radio ster5" type="radio" id="'.$review->getId().'_ster5" name="ster_input" value="5" />
                        <label class="ster_label" class for="'.$review->getId().'_ster5" title="5 sterren">★</label>

                        <input class="ster_radio ster4" type="radio" id="'.$review->getId().'_ster4" name="ster_input" value="4" />
                        <label class="ster_label" for="'.$review->getId().'_ster4" title="4 sterren">★</label>

                        <input class="ster_radio ster3" type="radio" id="'.$review->getId().'_ster3" name="ster_input" value="3" />
                        <label class="ster_label" for="'.$review->getId().'_ster3" title="3 sterren">★</label>

                        <input class="ster_radio ster2" type="radio" id="'.$review->getId().'_ster2" name="ster_input" value="2" />
                        <label class="ster_label" for="'.$review->getId().'_ster2" title="2 sterren">★</label>

                        <input class="ster_radio ster1" type="radio" id="'.$review->getId().'_ster1" name="ster_input" value="1" />
                        <label class="ster_label" for="'.$review->getId().'_ster1" title="1 ster">★</label>
                    </div>

                    <label for="newComment">Commentaar</label><textarea name="newComment" maxlength="255"></textarea><br>
                    <input class="button" type="submit" value="Opslaan">
                    
                </form>
                </div>';

        }
        ?>

    </div>
    <?php include 'includes/cookies.php'; ?>
    <!--Einde wrapper-->
    </div>
    <?php include 'includes/footer.php'; ?>
</body>

</html>