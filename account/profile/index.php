<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once $_SERVER['DOCUMENT_ROOT'] . '/tools/dbConnect.php';
session_start();

if (isset($_COOKIE['userData'])) {
    $userDataEncoded = $_COOKIE['userData'];
    $userData = json_decode($userDataEncoded, true); // 'true' pour obtenir un tableau associatif

    $email = $userData['email'];
    $name = $userData['name'];
    $familyName = $userData['familyName'];
    $role = $userData['role'];
} else {
    header("Location: /account/login");
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
    <script src="https://kit.fontawesome.com/f16a36bad3.js"
        crossorigin="anonymous">if (!document.cookie.includes("userData")) { window.location.href = '/account/login'; }</script>

    <title>Mon profil | Jeux Olympiques - Paris 2024</title>
</head>

<body>
    <?php include ($_SERVER['DOCUMENT_ROOT'] . '/views/header.php') ?>

    <h1>Mon profil :</h1>

    <div class="login-container" style="margin-top: 1rem;">
        <img src="https://cdn-icons-png.flaticon.com/512/4139/4139948.png" alt="Avatar">
        <h2>Mettre à jour mes informations</h2>
        <p>Modifiez les informations de votre profil.</p>
        <?php
        if (isset($_GET['result'])) {
            if ($_GET['result'] == "updatefailed") {
                echo "<p class='text' style='color:red; padding-left:0;'>⛔ Une erreur est survenue lors de la mise à jour de vos informations.</p>";
            } else if ($_GET["result"] == "updatesuccess") {
                echo "<p class='text' style='color:green; padding-left:0;'>✅ Vos informations ont été mises à jour avec succès.</p>";
            }
        }
        ?>
        <form action="/tools/updater.php" method="post">
            <div class="form-group">
                <label for="name"><span style="color:red;"><abbr title="Requis">*</abbr></span> Prénom</label>
                <input type="text" id="name" name="name" value="<?php echo $name ?>">
            </div>
            <div class="form-group">
                <label for="family_name"><span style="color:red;"><abbr title="Requis">*</abbr></span> Nom</label>
                <input type="text" id="family_name" name="family_name" value="<?php echo $familyName ?>">
            </div>
            <div class="form-group">
                <label for="email">Adresse mail</label>
                <input type="email" id="email" name="email" value="<?php echo $email ?>" disabled
                    style="cursor: not-allowed;">
            </div>
            <div class="form-group">
                <label for="password"><span style="color:red;"><abbr title="Requis">*</abbr></span> Mot de passe</label>
                <input type="password" id="password" name="password" required placeholder="••••••••" placeholder=" ">
            </div>

            <div class="form-group">
                <label for="role">Rôle</label>
                <input type="text" id="role" name="role" style="cursor: not-allowed;" value="<?php echo $role ?>"
                    disabled>
                <button type="submit" class="submit-button"><i class="fas fa-edit"></i>
                    Mettre à jour mes informations</i></button>
            </div>
        </form>

    </div>

    <?php include ($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php') ?>
</body>

</html>