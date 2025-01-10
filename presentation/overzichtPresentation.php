<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset=utf-8>
    <title>Prularia</title>
    <link rel="stylesheet" type="text/css" href="css/slick.css" />
    <link rel="stylesheet" type="text/css" href="css/slick-theme.css" />
    <link rel="stylesheet" href="css/overzicht.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="JavaScript/slick.min.js" defer></script>
    <script src="./JavaScript/overzicht.js" defer></script>
    <link rel="icon" href="./assets/prulariaicon.ico">
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <div class="wrapper">
        <section id="spotlight">
            <h1>Aanbevolen voor jou</h1>
            <div class="slider_container">
                <div class="artikels_slider">
                    <?php foreach ($spotlightArtikels as $artikel) {
                        $score = $artikelService->getGemiddeldeScorePerArtikel($artikel->getId());
                        $thumbnailPath = 'assets/thumbnailArtikels/artikelVoorbeeldImg.png';
                        if (file_exists('assets/thumbnailArtikels/' . $artikel->getId() . '.png')) {
                            $thumbnailPath = 'assets/thumbnailArtikels/' . $artikel->getId() . '.png';
                        } ?>
                        <div class="artikelContainer" id="artikel<?php echo $artikel->getId(); ?>" data-id="<?php echo $artikel->getId(); ?>">
                            <div class="fotoContainer">
                                <img src="<?php echo $thumbnailPath; ?>" loading="lazy" alt="<?php echo $artikel->getNaam(); ?> foto" title="<?php echo $artikel->getNaam(); ?>">
                            </div>
                            <div class="artikelinfo">
                                <p class="artikelNaam"><?php echo $artikel->getNaam(); ?></p>
                                <p class="artikelPrijs">&euro;<?php echo number_format((float) $artikel->getPrijs(), 2, '.', ''); ?></p>
                                <?php
                                if ($artikel->getVoorraad() == 0) { ?>
                                    <p class="artikelVoorraad">Niet meer in voorraad</p>
                                <?php } else if ($artikel->getVoorraad() < 5) { ?>
                                    <p class="artikelVoorraad">Nog <?php echo $artikel->getVoorraad(); ?> stuk(s) beschikbaar!</p>
                                <?php } ?>
                                <!--------------------------------EINDE GREG--------------------------------->
                                <?php if (is_null($score)) { ?>
                                    <div class="score geen">Geen reviews</div>
                                <?php } else { ?>
                                    <div class="score" style="--percent: <?php print($score / 5 * 100); ?>%;" title="<?php echo $score ?> van 5 steren">★★★★★</div>
                                <?php } ?>

                                <?php if ($artikel->getVoorraad() > 0) { ?>
                                    <a href="#" class="toevoegenAanWinkelmandKnop" data-voorraad="<?php echo $artikel->getVoorraad(); ?>" data-id="<?php echo $artikel->getId(); ?>" data-prijs="<?php echo $artikel->getPrijs(); ?>" data-naam="<?php echo $artikel->getNaam(); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-cart-plus" viewBox="0 0 16 16">
                                            <path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z" />
                                            <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7h-8.17zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                                        </svg>
                                    </a>
                                <?php } ?>
                            </div>
                            <button onclick="window.location.href = 'artikelInformatie.php?id=<?php echo $artikel->getId(); ?>';">Meer info</button>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
        <!-- Wrapper opslitsen in categorieen en producten -->
        <section id="overzicht">
            <div id="categorieKnopDiv">
                <h2>Categorieën</h2>
                <img id="categorieKnopImg" src="./assets/symboolBeneden.PNG" alt="Categorie knop">
            </div>

            <!-- ======= CATEGORIEËN (links) ======= -->
            <div id="categorieen">
                <h2>Categorieën</h2>
                <?php echo '<ul>';
                foreach ($categorieen as $hoofdId => $hoofdCategorie) {
                    $hoofdNaam = $hoofdCategorie['naam'];
                    $subCategorieen = $hoofdCategorie['subcategorieen'];

                    if (isset($_GET["hoofdcategorie"]) && ($hoofdId === (int)$_GET["hoofdcategorie"])) {
                        echo "<li class='hoofdCategorie'><a class='actief' id='$hoofdId' href='?hoofdcategorie=$hoofdId'>$hoofdNaam</a><ul>";
                    } else {
                        echo "<li class='hoofdCategorie'><a id='$hoofdId' href='?hoofdcategorie=$hoofdId'>$hoofdNaam</a><ul>";
                    }

                    foreach ($subCategorieen as $subId => $subCategorie) {
                        $subNaam = $subCategorie['naam'];
                        $subSubCategorieen = $subCategorie['subSubcategorieen'];

                        if (isset($_GET["categorie"]) && ($subId === (int)$_GET["categorie"])) {
                            echo "<li class='subCategorie'><a class='actief' id='$subId' href='?categorie=$subId'>$subNaam</a><ul>";
                        } else {
                            echo "<li class='subCategorie'><a id='$subId' href='?categorie=$subId'>$subNaam</a><ul>";
                        }

                        foreach ($subSubCategorieen as $subSubCategorie) {
                            $subSubId = $subSubCategorie['categorieId'];
                            $subSubNaam = $subSubCategorie['naam'];

                            if (isset($_GET["subcategorie"]) && ($subSubId === (int)$_GET["subcategorie"])) {
                                echo "<li class='subSubCategorie'><a class='actief' id='$subSubId' href='?subcategorie=$subSubId'>$subSubNaam</a></li>";
                            } else {
                                echo "<li class='subSubCategorie'><a id='$subSubId' href='?subcategorie=$subSubId'>$subSubNaam</a></li>";
                            }
                        }

                        echo "</ul></li>";
                    }

                    echo "</ul></li>";
                }
                echo '</ul>';
                ?>
            </div>


            <!-- ======= PRODUCTEN (rechts)======= -->
            <div id="producten">

                <!-- Begin zoekformulier toegevoegd -->
                <div id="zoekFormulier">
                    <form action="overzicht.php" method="GET">
                        <!-- Begin controle andere GET-waarden -->
                        <?php if (isset($_GET["categorie"])) { ?>
                            <input type="hidden" name="categorie" value="<?php echo $_GET["categorie"] ?>">
                        <?php } else if (isset($_GET["hoofdcategorie"])) { ?>
                            <input type="hidden" name="hoofdcategorie" value="<?php echo $_GET["hoofdcategorie"] ?>">
                        <?php } else if (isset($_GET["subcategorie"])) { ?>
                            <input type="hidden" name="subcategorie" value="<?php echo $_GET["subcategorie"] ?>">
                        <?php } else if (isset($_GET["zoekterm"])) { ?>
                            <input type="hidden" name="zoekterm" value="<?php echo $_GET["zoekterm"] ?>">
                        <?php } ?>
                        <!-- Einde controle andere GET-waarden -->
                        <input type="text" name="zoekterm" placeholder="Zoek artikelen">
                        <button type="submit"><i class="fas fa-search"></i></button>
                        <button type="button" id="wisZoekResultaten">Wis zoekresultaat</button>
                    </form>
                </div>
                <!-- Einde zoekformulier toegevoegd -->
                <div id="sorteerFormulier">
                    <form action="overzicht.php" method="GET">
                        <!-- Begin controle andere GET-waarden -->
                        <?php if (isset($_GET["categorie"])) { ?>
                            <input type="hidden" name="categorie" value="<?php echo $_GET["categorie"] ?>">
                        <?php } else if (isset($_GET["hoofdcategorie"])) { ?>
                            <input type="hidden" name="hoofdcategorie" value="<?php echo $_GET["hoofdcategorie"] ?>">
                        <?php } else if (isset($_GET["subcategorie"])) { ?>
                            <input type="hidden" name="subcategorie" value="<?php echo $_GET["subcategorie"] ?>">
                        <?php } else if (isset($_GET["zoekterm"])) { ?>
                            <input type="hidden" name="zoekterm" value="<?php echo $_GET["zoekterm"] ?>">
                        <?php } ?>
                        <!-- Einde controle andere GET-waarden -->
                        <select name="sorteerOp">
                            <option value="prijs" <?php echo ($sorteerOp === 'prijs') ? 'selected' : ''; ?>>Prijs</option>
                            <option value="naam" <?php echo ($sorteerOp === 'naam') ? 'selected' : ''; ?>>Naam</option>
                            <option value="score" <?php echo ($sorteerOp === 'score') ? 'selected' : ''; ?>>Beoordeling</option>
                        </select>
                        <select name="sorteerRichting">
                            <option value="ASC" <?php echo ($sorteerRichting === 'ASC') ? 'selected' : ''; ?>>Oplopend</option>
                            <option value="DESC" <?php echo ($sorteerRichting === 'DESC') ? 'selected' : ''; ?>>Aflopend</option>
                        </select>

                        <button type="submit" id="sorteerKnop">Sorteer</button>
                    </form>
                </div>
                <?php if ($validatieFouten) { ?>
                    <div class="foutmeldingen">
                        <p><?php echo htmlspecialchars($validatieFouten); ?></p>
                    </div>
                <?php } elseif (empty($artikels) && $zoekterm == "") { ?>
                    <div class="foutmeldingen">
                        <p>Er zijn geen artikelen gevonden.</p>
                    </div>
                <?php } elseif ($geenResultaten) { ?>
                    <div class="foutmeldingen">
                        <p>Geen resultaten gevonden voor "<?php echo htmlspecialchars($zoekterm); ?>"
                            <?php
                            if (isset($_GET["categorie"]) || isset($_GET["hoofdcategorie"]) || isset($_GET["subcategorie"])) { ?>
                                in de categorie "<?php
                                                    if (isset($_GET["categorie"])) {
                                                        echo getCategorieNaam($categorieen, $_GET["categorie"]);
                                                    } else if (isset($_GET["hoofdcategorie"])) {
                                                        echo getCategorieNaam($categorieen, $_GET["hoofdcategorie"]);
                                                    } else if (isset($_GET["subcategorie"])) {
                                                        echo getCategorieNaam($categorieen, $_GET["subcategorie"]);
                                                    }
                                                    echo '"';
                                                } ?>

                        </p>
                    </div>
                <?php } else { ?>
                <main class="ovezichtArtikels">
                <?php

                            // Controle of geen foutieve paginanummer is ingegeven
                            // if (isset($_GET["pagina"]) && ($_GET["pagina"] <= $aantalPaginas)) {

                            // (toonArtikel() in controller uitgeschreven)

                                foreach ($artikels as $artikel) {
                                    toonArtikel($artikel);
                                }
                            // Einde $artikels in de html structuur plaatsen
                        }


                ?>
                </main>
                <div class="paginaNavigatie">
                    <a class="pijl links" href="#" <?php if ($paginaNummer == 1) {
                                                        echo 'style="visibility: hidden;"';
                                                    } ?>></a>
                    <form id="paginaNummerForm">
                        <input id="paginaNummer" type="number" name="pagina" value="<?php echo $paginaNummer ?>" min="1" max="<?php echo $aantalPaginas; ?>">
                        <!-- Begin controle andere GET-waarden -->
                        <?php if (isset($_GET["categorie"])) { ?>
                            <input type="hidden" name="categorie" value="<?php echo $_GET["categorie"] ?>">
                        <?php } else if (isset($_GET["hoofdcategorie"])) { ?>
                            <input type="hidden" name="hoofdcategorie" value="<?php echo $_GET["hoofdcategorie"] ?>">
                        <?php } else if (isset($_GET["zoekterm"])) { ?>
                            <input type="hidden" name="zoekterm" value="<?php echo $_GET["zoekterm"] ?>">
                        <?php } ?>
                        <!-- Einde controle andere GET-waarden -->
                    </form>
                    <script>
                        var aantalPaginas = <?php echo json_encode($aantalPaginas); ?>;
                        var paginaNummerInput = document.getElementById('paginaNummer').value;
                        document.getElementById('paginaNummer').addEventListener('keydown', function(event) {
                            if (event.key === 'Enter'(paginaNummerInput <= aantalPaginas) && (paginaNummerInput > 0)) {
                                event.preventDefault();
                                document.getElementById('paginaNummerForm').submit();
                            }
                        });
                    </script>
                    Van
                        <?php
                        echo $aantalPaginas;
                        ?>
                        <a class="pijl rechts" href="#" <?php if ($paginaNummer == $aantalPaginas) {
                                                            echo 'style="visibility: hidden;"';
                                                        } ?>></a>
                    </div>
            </div>
        </section>
        <?php include 'includes/cookies.php'; ?>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>

</html>