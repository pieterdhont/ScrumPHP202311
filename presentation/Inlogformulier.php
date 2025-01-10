<?php
//inlogformulier.php
declare(strict_types=1); ?>

<!DOCTYPE HTML>
<html lang="nl">
<html>

<head>
    <meta charset=utf-8>
    <title>Prularia Login</title>
    <link rel="stylesheet" href="css/inlogformulier.css">
    <script src="./JavaScript/inloggen.js"></script>
    <link rel="icon" href="./assets/prulariaicon.ico">
</head>

<body>
    <?php include 'includes/header.php' ?>
    <p id="feedback"></p>
    <div class="login-container">
        <?php if (!isset($_COOKIE['gebruiker'])) { ?>
            <form method="post" id="login" action="inloggen.php?action=login">

                <h1>LOG IN</h1>

                <label for="log_email">Email adres</label>
                <input type="email" id="log_email" name="log_email" placeholder="Je email.." required maxlength="50">

                <br>

                <label for="log_paswoord">Wachtwoord</label>
                <input type="password" id="log_paswoord" name="log_paswoord" placeholder="Je wachtwoord.." required maxlength="50" minlength="8">

                <br>

                <input type="submit" value="Log in!" class="button">

            </form>
        <?php } else { ?>
            <form method="post" id="login" action="inloggen.php?action=logout">
                <input type="submit" name="unset_gebruiker" value="Uitloggen" class="button">
            </form>

        <?php } ?>

        <form method="post" id="register" action="inloggen.php?action=register">

            <h1>REGISTREER</h1>

            <label for="reg_voornaam">Voornaam</label>
            <input type="text" id="reg_voornaam" name="reg_voornaam" placeholder="Je voornaam.." required maxlength="45" value="<?php echo isset($_COOKIE['voornaam']) ? htmlspecialchars($_COOKIE['voornaam']) : ''; ?>">

            <br>

            <label for="reg_familienaam">Familienaam</label>
            <input type="text" id="reg_familienaam" name="reg_familienaam" placeholder="Je familienaam.." required maxlength="45" value="<?php echo isset($_COOKIE['familienaam']) ? htmlspecialchars($_COOKIE['familienaam']) : ''; ?>">

            <br>

            <label for="reg_email">Email adres</label>
            <input type="email" id="reg_email" name="reg_email" placeholder="Je email.." required maxlength="45" value="<?php echo isset($_COOKIE['email']) ? htmlspecialchars($_COOKIE['email']) : ''; ?>">

            <br>

            <label for="reg_paswoord">Wachtwoord</label>
            <input type="password" id="reg_paswoord" name="reg_paswoord" placeholder="Je wachtwoord.." required maxlength="50" minlength="8">

            <br>

            <label for="reg_paswoord2">Geef je wachtwoord opnieuw in</label>
            <input type="password" id="reg_paswoord2" name="reg_paswoord2" placeholder="Je wachtwoord.." required maxlength="50" minlength="8">

            <br>

            <!-- LEVERINGSADRES ========================== -->

            <h2>Leveringsadres</h2>

            <label for="reg_straat">Straat</label>
            <input type="text" id="reg_straat" name="reg_straat" placeholder="Straat.." required maxlength="100" value="<?php echo isset($_COOKIE['straat']) ? htmlspecialchars($_COOKIE['straat']) : ''; ?>">

            <br>

            <label for="reg_huisNummer">Huis Nummer</label>
            <input type="text" id="reg_huisNummer" name="reg_huisNummer" placeholder="Huisnummer.." required maxlength="5" value="<?php echo isset($_COOKIE['huisNummer']) ? htmlspecialchars($_COOKIE['huisNummer']) : ''; ?>">

            <br>

            <label for="reg_bus">Bus</label>
            <input type="text" id="reg_bus" name="reg_bus" placeholder="Bus.." maxlength="5" value="<?php echo isset($_COOKIE['bus']) ? htmlspecialchars($_COOKIE['bus']) : ''; ?>">

            <br>
            <label for="reg_postcode">Postcode</label>
            <input type="text" id="reg_postcode" name="reg_postcode" placeholder="Postcode.." required maxlength="5" value="<?php echo isset($_COOKIE['postcode']) ? htmlspecialchars($_COOKIE['postcode']) : ''; ?>">

            <br>

            <label for="reg_plaats">Plaats</label>
            <input type="text" id="reg_plaats" name="reg_plaats" placeholder="Plaats.." required maxlength="50" value="<?php echo isset($_COOKIE['plaats']) ? htmlspecialchars($_COOKIE['plaats']) : ''; ?>">

            <br>

            <!-- FACTURATIE ========================== -->

            <div class="checkbox-container">
                <label for="checkFacturatie">Facturatieadres hetzelfde als leveringsadres</label>
                <input type="checkbox" id="checkFacturatie" name="checkFacturatie" checked onclick="verbergFacturatie()">
            </div>

            <div id="facturatie">

                <h2>Facturatieadres</h2>

                <label for="reg_facturatieStraat">Facturatie Straat</label>
                <input type="text" id="reg_facturatieStraat" class="toggle_required" name="reg_facturatieStraat" placeholder="Facturatie straat.." required maxlength="100" value="<?php echo isset($_COOKIE['facturatieStraat']) ? htmlspecialchars($_COOKIE['facturatieStraat']) : ''; ?>">

                <br>

                <label for="reg_facturatieHuisNummer">Facturatie Huis Nummer</label>
                <input type="text" id="reg_facturatieHuisNummer" class="toggle_required" name="reg_facturatieHuisNummer" placeholder="Facturatie huis nummer.." required maxlength="5" value="<?php echo isset($_COOKIE['facturatieHuisNummer']) ? htmlspecialchars($_COOKIE['facturatieHuisNummer']) : ''; ?>">

                <br>

                <label for="reg_facturatieBus">Facturatie Bus</label>
                <input type="text" id="reg_facturatieBus" name="reg_facturatieBus" placeholder="Facturatie bus.." maxlength="5" value="<?php echo isset($_COOKIE['facturatieBus']) ? htmlspecialchars($_COOKIE['facturatieBus']) : ''; ?>">

                <br>

                <label for="reg_facturatiePostcode">Facturatie Postcode</label>
                <input type="text" id="reg_facturatiePostcode" class="toggle_required" name="reg_facturatiePostcode" placeholder="Postcode.." required maxlength="5" value="<?php echo isset($_COOKIE['facturatiePostcode']) ? htmlspecialchars($_COOKIE['facturatiePostcode']) : ''; ?>">

                <br>

                <label for="reg_facturatiePlaats">Facturatie Plaats</label>
                <input type="text" id="reg_facturatiePlaats" class="toggle_required" name="reg_facturatiePlaats" placeholder="Facturatie Plaats.." required maxlength="50" value="<?php echo isset($_COOKIE['facturatiePlaats']) ? htmlspecialchars($_COOKIE['facturatiePlaats']) : ''; ?>">

                <br>
            </div>

            <input type="submit" value="Registreer!" class="button">

        </form>
        <?php include 'includes/cookies.php'; ?>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>

</html>