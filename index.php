<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tools/dbConnect.php';
session_start();

if (isset($_COOKIE['userData'])) {
    $userDataEncoded = $_COOKIE['userData'];
    $userData = json_decode($userDataEncoded, true); // 'true' pour obtenir un tableau associatif

    $email = $userData['email'];
    $name = $userData['name'];
    $familyName = $userData['familyName'];
    $role = $userData['role'];
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="Jeux Olympiques - Paris 2024" />
    <meta name="description" content="Site non-officiel des Jeux-Olympiques Paris 2024 (SAÉ Web)" />
    <meta name="keywords" content="Jeux-Olympiques, Paris, 2024, Informatique, SAÉ" />
    <meta name="author" content="Juliette CHARPENTIER & Loris BALOCCHI" />
    <meta name="robots" content="index, follow" />
    <meta name="revisit-after" content="7 days" />
    <meta name="language" content="French" />
    <meta name="theme-color" content="#d41942" />
    <link rel="stylesheet" href="/styles/main.css" />
    <link rel="stylesheet" href="/styles/header.css" />
    <link rel="stylesheet" href="/styles/footer.css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="icon" type="image/png" sizes="32x32"
        href="https://tickets.paris2024.org/obj/media/FR-Paris2024/specialLogos/favicons/favicon-32x32.png" />

    <title>Accueil | Jeux Olympiques - Paris 2024</title>
</head>

<body>
    <?php include ($_SERVER['DOCUMENT_ROOT'] . '/views/header.php') ?>

    <!-- code de la page ici -->
    <h1>Accueil</h1>
    <p class="text">Bienvenue <?php
    if (isset($_GET['res'])) {
        if ($_GET['res'] == "login-succeeded") {
            echo "<span >" . $name . " " . $familyName . ",</span>";
        }
    }
    ?> sur le site des Jeux Olympiques de Paris 2024 !</p>
    <p class="text">Vous trouverez ici toutes les informations nécessaires pour suivre les Jeux Olympiques de Paris
        2024.</p>
    <p class="text">Vous pourrez également créer un compte pour accéder à des fonctionnalités supplémentaires.</p>

    <?php include ($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php') ?>

    <script src="https://kit.fontawesome.com/f16a36bad3.js" crossorigin="anonymous"></script>
</body>

</html>