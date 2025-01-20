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
    <script src="/scripts/dateChecker.js" defer></script>
    <title>Jeux Olympiques - Paris 2024</title>
</head>

<body>
    <?php include ($_SERVER['DOCUMENT_ROOT'] . '/views/header.php') ?>

    <?php
    if (!isset($_COOKIE['userData'])) {
        echo "<h1>Créer un évènement</h1>";
        echo "<p class='text'>Vous n'êtes pas autorisé à accéder à cette page.</p>";
        echo "<p class='text'>Redirection vers l'accueil dans 5 secondes...</p>";
        header("refresh:5; url=/");
        include $_SERVER['DOCUMENT_ROOT'] . '/views/footer.php';
        die();
    }


    $role = $userData['role'];

    if (($role == 'Administrateur') or ($role == 'Organisateur')) {
        echo "<div class='event-container'>";
        echo "<img src='https://cdn-icons-png.flaticon.com/512/2538/2538566.png' alt='Avatar'>";
        echo "<h2 class='event-title'>Créer un nouvel évènement</h2>";
        echo "<p class='event-subtitle'>Remplissez le formulaire suivant afin de créer un nouvel évènement.</p>";
        echo "<form method='POST' action='/events/new/eventCreate.php'>";
        echo "<div class='form-group'>";
        if (isset($_GET['result'])) {
            if ($_GET['result'] == "event-creation-failed") {
                echo "<p class='text' style='color:red; padding-left:0; text-align:center;'>&nbsp; &nbsp; &nbsp; &nbsp;⛔ Une erreur est survenue, l'évènement n'a pas été créé.</p>";
            } else if ($_GET["result"] == "event-creation-succeded") {
                echo "<p class='text' style='color:green; padding-left:0; text-align:center;'>&nbsp; &nbsp; &nbsp; &nbsp;✅ L'évènement a bien été créé.</p>";
            }
        }
        echo "<div class='form-group'>";
        echo "<label for='eventName'>Nom de l'évènement</label>";
        echo "<input type='text' name='eventName' id='eventName' required>";
        echo "</div>";
        echo "<div class='form-group'>";
        echo "<label for='eventDiscipline'>Discipline</label>";
        echo "<select name='eventDiscipline' id='eventDiscipline'>";
        $disciplines = mysqli_query($db, "SELECT discipline FROM `olympic_discipline` ");
        while ($row = mysqli_fetch_assoc($disciplines)) {
            echo "<option>" . $row['discipline'] . "</option>";
        }
        echo "</select>";
        echo "</div>";
        echo "<div class='form-group'>";
        echo "<label for='eventDate'>Date de l'évènement</label>";
        echo "<input type='date' name='eventDate' id='eventDate' required>";
        echo "</div>";
        echo "<div class='form-group'>";
        echo "<label for='eventLocation'>Lieu de l'évènement<select name='eventLocation' id='eventLocation'>";
        $locations = mysqli_query($db, "SELECT venue_info FROM `olympic_location` ");
        while ($row = mysqli_fetch_assoc($locations)) {
            echo "<option>" . $row['venue_info'] . "</option>";
        }
        echo "</select>";
        echo "</label>";
        echo "</div>";
        echo "<div class='form-group'>";
        echo "<label for='eventDescription'>Description de l'évènement</label>";
        echo "<input type='textarea' name='eventDescription' id='eventDescription' required>";
        echo "</div>";
        echo "<div class='form-group'>";
        echo "<label for='eventRole'>Rôles pouvant s'inscrire (<b><i>en sélectionner au moins un</i></b>)</label>";
        echo "<div>";
        echo "<input type='checkbox' name='eventRole[]' id='spectateur' value='Spectateur'>";
        echo "<label for='spectateur'>";
        echo "<p>Spectateur</p>";
        echo "</label>";
        echo "</div>";
        echo "<div>";
        echo "<input type='checkbox' name='eventRole[]' id='sportif' value='Sportif'>";
        echo "<label for='sportif'>";
        echo "<p>Sportif</p>";
        echo "</label>";
        echo "</div>";
        echo "<div>";
        echo "<input type='checkbox' name='eventRole[]' id='organisateur' value='Organisateur'>";
        echo "<label for='organisateur'>";
        echo "<p>Organisateur</p>";
        echo "</label>";
        echo "</div>";
        echo "<button type='submit' class='submit-button'><i class='fas fa-calendar-plus'></i> Créer</i></button>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
        echo "</div>";

    } else {
        echo "<p class='text'>Vous n'êtes pas autorisé à accéder à cette page.</p>";
        echo "<p class='text'>Redirection vers l'accueil dans 5 secondes...</p>";
        header("refresh:5; url=/");
        include ($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
        die();
    }
    ?>



    <?php include ($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php') ?>
</body>

</html>