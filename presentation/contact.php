<!DOCTYPE HTML>
<!-- ./presentation/contact.php -->
<html lang="nl">

<head>
    <meta charset=utf-8>
    <title>Prularia Contact</title>
    <link rel="stylesheet" href="css/contact.css">
    <link rel="icon" href="./assets/prulariaicon.ico">
</head>

<body>
    <?php include 'includes/header.php' ?>
    <div class="wrapper">
        <?php
        if (isset($_SESSION['success_message'])) {
            echo '<div class="success-message">' . $_SESSION['success_message'] . '</div>';

            unset($_SESSION['success_message']);
        }
        ?>

        <!-- Contactgegevens -->
        <div class="contact-container">
            <div class="contact-details">
                <h2>Contactgegevens</h2>
                <h4>Prularia B.V.</h4>
                <h5>Adres:</h5>
                <p>Straatnaam 123</p>
                <p>1234 Stad, België</p>
                <h5>Email:</h5>
                <p>info@prularia.be</p>
                <h5>Telefoon:</h5>
                <p>+32 123 456 789</p>
            </div>

            <!-- Contactformulier -->
            <div class="contact-form">
                <h2>Stuur ons een bericht</h2>
                <form id="contactForm" action="./contact.php" method="post">

                    <label for="email">
                        <h5>Email:</h5>
                    </label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($standaardEmail) ?>"
                        placeholder="Uw email" required>

                    <label for="bericht">
                        <h5>Bericht:</h5>
                    </label>
                    <textarea id="bericht" name="bericht" rows="4" placeholder="Uw bericht" required></textarea>

                    <button class="button" type="submit">Verstuur</button>
                </form>
            </div>
        </div>
        <?php include 'includes/cookies.php'; ?>
    </div>
    <?php include 'includes/footer.php' ?>
</body>

</html>