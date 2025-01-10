<?php

declare(strict_types=1);

//print_r($artikelSpecifiek);

//print_r($categorieen);
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset=utf-8>
    <title>Prularia</title>
    <link rel="stylesheet" type="text/css" href="css/slick.css" />
    <link rel="stylesheet" type="text/css" href="css/slick-theme.css" />
    <link rel="stylesheet" href="css/productInfo.css">
    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="JavaScript/slick.min.js" defer></script>
    <script type="text/javascript" src="JavaScript/productInfo.js" defer></script>
    <link rel="icon" href="./assets/prulariaicon.ico">
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <!-- Overzicht weergeven van de onderverdeling van categorieën -->

    <div class="wrapper">

        <div class="container">

            <section class="links_sectie">

                <!-- Afbeelding van artikel -->
                <div class="fotoContainer">
                    <img src="<?php echo $thumbnailPath; ?>" loading="lazy" alt="<?php echo $artikelSpecifiek->getNaam(); ?> foto" title="<?php echo $artikelSpecifiek->getNaam(); ?>">
                </div>
            </section>
            <section class="rechts_sectie">
                <nav id="categorie-pad">
                    <?php
                    $hoofdCategorieId = $artikelSpecifiek->getHoofdCategorieId();
                    ?>
                    <a href="overzicht.php?hoofdcategorie=<?php if ($hoofdCategorieId == 3) { echo 1; } else { echo $hoofdCategorieId; }; ?>"><?php if ($hoofdCategorieId == 3) { echo "Huishouden"; } else { echo $artikelService->getHoofdCategorieNaamByCategorieId($hoofdCategorieId); }; ?></a>

                    <!--PIJL HIER-->
                    <img src="assets/right-arrow.png" alt="">
                    <a href="overzicht.php?categorie=<?php if ($hoofdCategorieId == 3) { echo 3; } else { echo $artikelSpecifiek->getCategorieId(); }; ?>"><?php if ($hoofdCategorieId == 3) { echo "Aan tafel"; } else { echo $artikelSpecifiek->getCategorieNaam(); } ?></a>
                </nav>
                <h1>
                    <span class="artikelInfo" id="artikelNaam">
                        <?php echo $artikelSpecifiek->getNaam(); ?>
                    </span>
                </h1>

                <!-- Container met details van artikel -->
                <div class="artikelDetails_container">
                    <div class="main_details">
                        <div class="artikel_info">
                            <p>
                                <strong>Beschrijving: </strong>
                                <span class="artikelInfo" id="artikelBeschrijving">
                                    <?php echo $artikelSpecifiek->getBeschrijving(); ?>
                                </span>
                            </p>
                            <p>
                                <strong>Prijs: </strong>
                                <span class="artikelInfo" id="artikelPrijs">
                                    €<?php echo number_format((float) $artikelSpecifiek->getPrijs(), 2, '.', ''); ?>
                                </span>
                            </p>
                            <p>
                                <strong>Rating: </strong>
                                <span class="artikelInfo" id="artikelRating">
                                    <?php if ($gemiddeldeScore > 0) {
                                        echo $gemiddeldeScore;
                                    } else {
                                        echo "Geen rating beschikbaar";
                                    }; ?>
                                </span> <?php if ($gemiddeldeScore > 0) {
                                            echo "ster(ren)";
                                        } ?>
                            </p>
                            <p>
                                <strong>Levertijd: </strong>
                                <span class="artikelInfo" id="artikelLevertijd">
                                    <?php echo $artikelSpecifiek->getLevertijd(); ?>
                                </span> werkdag
                            </p>
                            <p>
                                <strong>Voorraad: </strong>
                                <span class="artikelInfo" id="artikelVoorraad">
                                    <?php echo $artikelSpecifiek->getVoorraad(); ?>
                                </span> artikelen
                            </p>
                        </div>
                    </div>
                    <div class="extra_details">
                        <div class="technische_specificaties">
                            <!-- Nog nagaan of Technische specificaties nodig is. Vaak staat veel info bij Beschrijving -->
                            <h2>Technische specificaties</h2>
                            <p>
                                <strong>EAN: </strong>
                                <span class="artikelInfo" id="ean">
                                    <?php echo $artikelSpecifiek->getEAN(); ?>
                                </span>
                            </p>
                            <p>
                                <strong>Gewicht:</strong>
                                <span class="artikelInfo" id="gewicht">
                                    <?php echo $artikelSpecifiek->getGewicht(); ?>
                                </span> gram
                            </p>
                        </div>
                        <div class="leverancier_info">
                            <h2>Leverancier informatie</h2>
                            <p>
                                <strong>Leveranciernaam: </strong>
                                <span class="artikelInfo" id="gewicht">
                                    <?php echo $artikelSpecifiek->getArtikelLeverancierNaam(); ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <?php if ($artikelSpecifiek->getVoorraad() > 0) { ?>
                    <button class="button" id="toevoegenMetAantalAanWinkelmand" data-id="<?php echo $artikelSpecifiek->getId(); ?>" data-prijs="<?php echo $artikelSpecifiek->getPrijs(); ?>" data-naam="<?php echo $artikelSpecifiek->getNaam(); ?>" data-voorraad="<?php echo $artikelSpecifiek->getVoorraad(); ?>">Toevoegen aan winkelmand</button>
                    <?php if ($artikelSpecifiek->getVoorraad() < 5) { ?>
                        <p class="artikelVoorraad">Nog <?php echo $artikelSpecifiek->getVoorraad(); ?> stuk(s) beschikbaar!</p>
                    <?php } ?>
                <?php } else { ?>
                    <p class="artikelVoorraad">Niet meer in voorraad</p>
                <?php } ?>

                <!-- Container met overzicht van reviews -->
                <div class="artikelReviews">
                    <h2>Beoordelingen van klanten: </h2>
                    <div class="slider_container">
                        <div class="reviews_slider">
                            <!-- Review bevat: Klantnaam via klantId, score, commentaar, datum  -->
                            <?php
                            if ($artikelSpecifiekReviews) {
                                foreach ($artikelSpecifiekReviews as $review) {
                                    $score = $review->getScore(); ?>
                                    <div class="review">
                                        <p>Auteur:
                                            <Strong>
                                                <?php
                                                if ($gebruikerService->getGebruikerByKlantId(intval($review->getKlantId())) !== null) {
                                                    echo $gebruikerService->getGebruikerByKlantId($review->getKlantId())->getVoornaam();
                                                } else {
                                                    echo "onbekend";
                                                } ?>
                                            </Strong>
                                        </p>
                                        <div class="score" style="--percent: <?php print($score / 5 * 100); ?>%;" title="<?php echo $score ?> van 5 steren">★★★★★</div>
                                        <p title="Commentaar"><?php echo $review->getCommentaar(); ?></p>
                                        <p title="datum">
                                            <time datetime="<?php echo $review->getDatum(); ?>"> <?php echo $review->getDatum(); ?> </time>
                                        </p>
                                    </div>
                            <?php }
                            } else {
                                echo '<div class="review geen">Voor dit artikel werden nog geen reviews geschreven.</div>';
                            } ?>
                        </div>
                    </div>
                </div>

                <?php

                if ($reviewToegelaten) { ?>
                    <form action="artikelInformatie.php?id=<?php echo $artikelSpecifiek->getId(); ?>&action=plaatsReview" method="POST">
                        <h4>Schrijf een beoordeling over het product: </h4>
                        <div class="sterrenScore">
                            <input class="ster_radio" type="radio" id="ster5" name="rating" value="5" />
                            <label class="ster_label" class for="ster5" title="5 sterren">★</label>

                            <input class="ster_radio" type="radio" id="ster4" name="rating" value="4" />
                            <label class="ster_label" for="ster4" title="4 sterren">★</label>

                            <input class="ster_radio" type="radio" id="ster3" name="rating" value="3" />
                            <label class="ster_label" for="ster3" title="3 sterren">★</label>

                            <input class="ster_radio" type="radio" id="ster2" name="rating" value="2" />
                            <label class="ster_label" for="ster2" title="2 sterren">★</label>

                            <input class="ster_radio" type="radio" id="ster1" name="rating" value="1" />
                            <label class="ster_label" for="ster1" title="1 ster">★</label>
                        </div>
                        <textarea id="commentaar" name="commentaar" placeholder="Geef ons je mening.." maxlength="255" autofocus></textarea>
                        <input type="submit" value="Plaatsen">
                    </form>

                <?php } ?>
            </section>

        </div>
        <?php include 'includes/cookies.php'; ?>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>

</html>