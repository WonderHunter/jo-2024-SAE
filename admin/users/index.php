<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tools/dbConnect.php';
session_start();

if (isset($_COOKIE['userData'])) {
    $userDataEncoded = $_COOKIE['userData'];
    $userData = json_decode($userDataEncoded, true);

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
    <h1>Gestion des utilisateurs</h1>
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
    } else {
        echo "<p class='text'>Bienvenue sur le panel de gestion des utilisateurs.</p>";
        echo "<p class='text'>Vous pouvez ici gérer les utilisateurs.</p>";
        echo "<p class='text'>Que souhaitez-vous faire ?</p>";

        if (isset($_GET['res'])) {
            $oldEmail = ($_GET['res']);
            echo "<p class='text' style='color:yellow; padding-left:0; text-align:center;'>L'utilisateur " . $oldEmail . " a bien été supprimé.</p>";
        }
        if (isset($_GET['newuser'])) {
            $newUserEmail = ($_GET['newuser']);
            echo "<p class='text' style='color:green; padding-left:0; text-align:center;'>L'utilisateur " . $newUserEmail . " a bien été créé.🦆</p>";

        }
        $users = mysqli_query($db, "SELECT * FROM `user`");
        echo "<div class='events-flex-container'>";
        echo "<div class='scrollable'>";
        echo "<table class='event-table'>";
        echo "<tr>";
        echo "<th>Adresse mail</th>";
        echo "<th>Nom</th>";
        echo "<th>Prénom</th>";
        echo "<th>Rôle</th>";
        echo "<th>Actions</th>";
        echo "</tr>";
        while ($row = mysqli_fetch_assoc($users)) {
            echo "<tr>";
            echo "<td>" . $row['mail'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['family_name'] . "</td>";
            echo "<td>" . $row['role'] . "</td>";
            echo "<td><a href='/admin/users/edit?usermail=" . $row['mail'] . "'>Modifier</a> | <a href='/admin/users/delete?oldemail=" . $row['mail'] . "'>Supprimer</a></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
        echo "</div>";
        echo "<button class='submit-button new-event' onclick=\"window.location.href = '/admin/users/add';\">Ajouter un utilisateur</button>";
        echo "<button class='submit-button new-event' onclick=\"window.location.href = '/admin/';\">Retour au panel d'administration</button>";
        echo "<button class='submit-button new-event' onclick=\"window.location.href = '/';\">Retour à l'accueil</button>";

        //contenu de la page admin
    } ?>




    <?php include ($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php') ?>
</body>

</html>