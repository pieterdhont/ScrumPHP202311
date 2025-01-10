<?php

declare(strict_types=1);

?>

<!DOCTYPE html>
<html lang="nl">

<head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/bestelPagina.css">
        <title>Prularia: Feedback</title>
        <link rel="icon" href="./assets/prulariaicon.ico">
</head>

<body>
    <!-- Invoegen header -->
    <?php include("includes/header.php"); ?>

    <div class="wrapper">
        <div class="feedbackContainer">
            <p class="bestellingFeedback">
                Bedankt voor uw bestelling! <br> 
                <span class="groteFeedback">We hebben deze goed ontvangen.</span> <br> 
                <span class="kleineFeedback"> Wij gaan meteen voor u aan het werk! </span>
            </p> <br>
            <p>Klik <a href="Overzicht.php">hier</a> om terug te gaan naar het overzicht.</p>

            </div>
        <?php include 'includes/cookies.php'; ?>
        </div>
    <?php include 'includes/footer.php'; ?>
</body>

</html>