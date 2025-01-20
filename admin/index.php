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
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/views/header.php'; ?>
    <h1>Panel d'administration</h1>
    <?php
    if (!isset($_COOKIE['userData'])) {
        echo "<p class='text'>Vous n'êtes pas autorisé à accéder à cette page.</p>";
        echo "<p class='text'>Redirection vers l'accueil dans 5 secondes...</p>";
        header("refresh:5; url=/");
        die();
    }

    $role = $userData['role'];
    $stmt = mysqli_prepare($db, "SELECT * FROM user WHERE mail = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $role = $row["role"];
    }
    if ($role != 'Administrateur') {
        echo "<p class='text'>Vous n'êtes pas autorisé à accéder à cette page.</p>";
        echo "<p class='text'>Redirection vers l'accueil dans 5 secondes...</p>";
        //remet à jour le cookie de l'utilisateur pour remettre son rôle à jour
        setcookie('userData', json_encode(array('email' => $email, 'name' => $name, 'familyName' => $familyName, 'role' => $role)), time() + 365 * 24 * 3600, '/');

        header("refresh:5; url=/");
        die();
    } else { // Si l'utilisateur est un administrateur : accès à la page
        echo "<p class='text'>Bienvenue sur le panneau d'administration du site.</p>";
        echo "<p class='text'>Vous pouvez ici gérer les utilisateurs.</p>";
        echo "<p class='text'>Que souhaitez-vous faire ?</p>";
        echo '<button class="submit-button new-event" onclick="window.location.href = \'/admin/users\';">Gérer les utilisateurs</button>';


        ;
        //contenu de la page admin
    } ?>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/views/footer.php'; ?>
</body>

</html>