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
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/views/header.php';
    if (!isset($_COOKIE['userData'])) {
        echo "<p class='text'>Vous n'êtes pas autorisé à accéder à cette page.</p>";
        echo "<p class='text'>Redirection vers l'accueil dans 5 secondes...</p>";
        header("refresh:5; url=/");
        include $_SERVER['DOCUMENT_ROOT'] . '/views/footer.php';
        die();
    } ?>
    <h1>Mes réservations</h1>
    <?php echo "<p class='text'>Bienvenue <span>" . $name . " " . $familyName . ".</span></p>";

    ?>
    <!-- code de la page ici -->
    <?php
    if (isset($_GET['res'])) {
        if ($_GET['res'] == "booking-succeeded") {
            echo "<p class='text'>✅ Votre réservation a bien été prise en compte.</p>";
        } else if ($_GET['res'] == "booking-failed") {
            echo "<p class='text'>❌ La réservation a échoué. Veuillez réessayer.</p>";
        } else if ($_GET['res'] == "cancellation-failed") {
            echo "<p class='text'>❌ L'annulation a échoué. Veuillez réessayer.</p>";
        } else if ($_GET['res'] == "cancellation-succeeded") {
            $eventTitleFetched = $_GET['eventtitle'];
            echo "<p class='text'>✅ L'annulation de $eventTitleFetched a bien été prise en compte.</p>";
        }
    }
    //afficher la liste des évènements auxquels l'utilisateur est inscrit (avec mysqli) (table booking)
    $stmt = mysqli_prepare($db, "SELECT * FROM booking WHERE mail = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 0) {
        echo "<p class='text'>Vous n'êtes inscrit à aucun évènement pour le moment.</p>";
    } else {
        echo "<p class='text'>Voici la liste des évènements auxquels vous êtes inscrit :</p>";
        echo "<div class='events-flex-container'>";
        echo "<div class='scrollable'>";
        echo "<table class='event-table'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th scope='col'>Nom de l'évènement</th>";
        echo "<th scope='col'>Date</th>";
        ;
        echo "<th scope='col'>Lieu</th>";
        echo "<th scope='col'>Action</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        // sélectionner les évènements auxquels l'utilisateur est inscrit (avec mysqli et à l'aide de $email) (utiliser cette requete ? SELECT * FROM booking WHERE mail="$email";)
        while ($booking = mysqli_fetch_assoc($result)) {
            $eventID = $booking['id'];
            $stmt = mysqli_prepare($db, "SELECT * FROM event WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $eventID);
            mysqli_stmt_execute($stmt);
            $eventResult = mysqli_stmt_get_result($stmt);
            $event = mysqli_fetch_assoc($eventResult);


            echo "<tr>";
            echo "<td>" . $event['title'] . "</td>";
            echo "<td>" . date('d/m/Y', strtotime($event['date'])) . "</td>";
            echo "<td>" . $event['location'] . "</td>";
            echo "<td><a href='/events/book/cancel.php?usermail=$email&id=" . $event['id'] . "'>Annuler</a></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
        echo "</div>";
    }

    //echo "<td><a href='/events/book/cancel.php?id=" . $event['id'] . "'>Annuler</a></td>";
    
    ?>

    <?php include ($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php') ?>
</body>

</html>