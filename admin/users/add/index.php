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
    <link rel="stylesheet" href="/styles/main.css" />
    <link rel="stylesheet" href="/styles/header.css" />
    <link rel="stylesheet" href="/styles/footer.css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="icon" type="image/png" sizes="32x32"
        href="https://tickets.paris2024.org/obj/media/FR-Paris2024/specialLogos/favicons/favicon-32x32.png" />
    <script src="https://kit.fontawesome.com/f16a36bad3.js" crossorigin="anonymous"></script>
    <title>Jeux Olympiques - Paris 2024</title>
</head>

<body>
    <?php include ($_SERVER['DOCUMENT_ROOT'] . '/views/header.php') ?>
    <!-- code de la page ici -->
    <div class="login-container">
        <img src="https://cdn-icons-png.flaticon.com/512/4139/4139948.png" alt="Avatar">
        <h2>Créer un compte</h2>
        <p>Créez un nouveau compte afin d'accéder à l'entièreté du site.</p>
        <form action="/admin/users/add/add.php" method="post">
            <div class="form-group">
                <label for="name"><span style="color:red;"><abbr title="Requis">*</abbr></span> Prénom</label>
                <input type="text" id="name" name="name" placeholder="Jean" required placeholder=" ">
            </div>
            <div class="form-group">
                <label for="family_name"><span style="color:red;"><abbr title="Requis">*</abbr></span> Nom</label>
                <input type="text" id="family_name" name="family_name" placeholder="DUPONT" required placeholder=" ">
            </div>
            <div class="form-group">
                <label for="email"><span style="color:red;"><abbr title="Requis">*</abbr></span> Adresse mail</label>
                <input type="email" id="email" name="email" placeholder="username@example.com" required placeholder=" ">
            </div>
            <div class="form-group">
                <label for="password"><span style="color:red;"><abbr title="Requis">*</abbr></span> Mot de passe</label>
                <input type="password" id="password" name="password" required placeholder="••••••••" placeholder=" ">
            </div>


            <div class='form-group'>
                <label for='role'>Rôle à assigner</label>
                <select name='role' id='role'>
                    <option>Administrateur</option>
                    <option>Organisateur</option>
                    <option>Sportif</option>
                    <option>Spectateur</option>

                </select>
            </div>

            <button type="submit" class="submit-button"><i class="fas fa-sign-in-alt"></i>
                Créer un compte</i></button>
        </form>
    </div>

    <?php include ($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php') ?>
</body>

</html>