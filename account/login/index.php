<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once $_SERVER['DOCUMENT_ROOT'] . '/tools/dbConnect.php';
session_start();

if (isset($_COOKIE['userData'])) {
    header("Location: /account/profile");
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../../styles/main.css" />
    <link rel="stylesheet" href="../../styles/header.css" />
    <link rel="stylesheet" href="../../styles/footer.css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="icon" type="image/png" sizes="32x32"
        href="https://tickets.paris2024.org/obj/media/FR-Paris2024/specialLogos/favicons/favicon-32x32.png" />
    <title>Connexion | Jeux Olympiques - Paris 2024</title>
    <script src="https://kit.fontawesome.com/f16a36bad3.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php include ($_SERVER['DOCUMENT_ROOT'] . '/views/header.php') ?>

    <div class="login-container">
        <img src="https://cdn-icons-png.flaticon.com/512/4139/4139948.png" alt="Avatar">
        <h2 class="login-title">Connexion à votre compte</h2>
        <p class="login-subtitle">Connectez-vous afin d'accéder à l'entièreté du site.</p>
        <form class="form" action="/account/login/login.php" method="post">
            <div class="form-group">
                <label for="email">Adresse mail</label>
                <input type="email" id="email" name="email" placeholder="username@example.com" required placeholder=" ">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required placeholder="••••••••" placeholder=" ">
            </div>
            <?php
            if (isset($_GET['res'])) {
                if ($_GET['res'] == "login-failed") {
                    echo "<p class='text' style='color:red; padding-left:0; padding-right:0; width:fit-content;'>⛔ Adresse mail ou mot de passe incorrect.</p>";
                }
            }
            ?>
            <button type="submit" class="submit-button"><i class="fas fa-sign-in-alt"></i>
                Connexion</i></button>
        </form>
        <p class="no-account">Vous n'avez pas de compte ? <a href="/account/register">Créez un compte pour commencer
                !</a></p>

    </div>

    <?php include ($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php') ?>

</body>

</html>