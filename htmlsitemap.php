<?php
// htmlsitemap.php
declare(strict_types=1);
require_once('data/autoloader.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prularia Sitemap</title>
    <link rel="stylesheet" href="css/sitemap.css">
</head>

<body>
    <?php include 'includes/header.php' ?>
    <div class="sitemap-container">
        <h1>Sitemap</h1>

        <ul>
            <!-- Homepagina (overzichtspagina) -->
            <li><a href="./overzicht.php">Homepagina (Overzichtspagina)</a></li>

            <!-- Contactpagina -->
            <li><a href="./contact.php">Contactpagina</a></li>

            <!-- Inlog- en registratiepagina -->
            <li><a href="./inloggen.php">Inlog- en registratiepagina</a></li>

            <!-- Besteloverzichtpagina -->
            <li><a href="./bestellingDoorgeven.php">Besteloverzichtspagina</a></li>

            <!-- faqpagina -->
            <li><a href="./faq.php">FAQ-pagina</a></li>
        </ul>
    </div>
    <?php include 'includes/footer.php' ?>
</body>

</html>