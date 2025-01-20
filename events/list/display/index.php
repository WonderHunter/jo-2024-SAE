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

    <?php include ($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
    if (isset($_COOKIE['userData'])) {
        if (isset($_GET['location'])) {
            $location = $_GET['location'];
            echo "<h2>Évènements à $location pour les $role" . "s" . "</h2>";
            if ($role == 'Administrateur') {
                $query = "SELECT * FROM event WHERE location = '$location'";
            } else {
                $query = "SELECT * FROM event WHERE location = '$location' AND role LIKE '%$role%'";
            }
            $result = mysqli_query($db, $query);
            echo "<div class='events-flex-container'>";
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $eventID = htmlspecialchars($row['id'], ENT_QUOTES);
                    $title = htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');
                    $description = htmlspecialchars($row['description'], ENT_QUOTES);
                    $event_type = htmlspecialchars($row['event_type'], ENT_QUOTES);
                    $date = date('d/m/Y', strtotime(htmlspecialchars($row['date'])));
                    $location = htmlspecialchars($row['location'], ENT_QUOTES);
                    $authorized_roles = htmlspecialchars($row['role'], ENT_QUOTES);
                    $guest_count = htmlspecialchars($row['guest_count'], ENT_QUOTES);
                    $creator = htmlspecialchars($row['creator'], ENT_QUOTES);
                    echo "<div class='event-card'>";
                    echo "<h3>$title</h3>";
                    echo "<p class='text'>Type d'évènement : $event_type</p>";
                    echo "<p class='text'>Date : $date</p>";
                    echo "<p class='text'>Lieu : $location</p>";
                    echo "<p class='text'>Nombre de participants : $guest_count</p>";
                    echo "<p class='text'>(Roles autorisés) : $authorized_roles</p>";
                    echo "<p class='text'>$description</p>";
                    if ($role != 'Administrateur') {
                        if ($role != 'Administrateur') {
                            echo "<button class='submit-button' onclick=\"window.location.href = '/events/book/book.php?usermail=$email&event=$eventID';\">Réserver une place</button>";
                        }
                    }
                    if ($role == 'Sportif') {
                        echo "<button class='submit-button' onclick=\"window.location.href = '/events/participate/participate.php?usermail=$email&event=$eventID';\">Concourir</button>";
                    }
                    echo "</div>";
                }
            } else {
                echo "<p class='text'>Aucun évènement trouvé à cet endroit.</p>";
                echo "</div>";
            }
        }


        if (isset($_GET['date'])) {
            $date = $_GET['date'];
            echo "<h2>Évènements le $date pour $role</h2>";
            if ($role == 'Administrateur') {
                $query = "SELECT * FROM event WHERE date = '$date'";
            } else {
                $query = "SELECT * FROM event WHERE date = '$date' AND role LIKE '%$role%'";
            }
            $result = mysqli_query($db, $query);
            echo "<div class='events-flex-container'>";
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $eventID = htmlspecialchars($row['id'], ENT_QUOTES);
                    $title = htmlspecialchars($row['title'], ENT_QUOTES);
                    $description = htmlspecialchars($row['description'], ENT_QUOTES);
                    $event_type = htmlspecialchars($row['event_type'], ENT_QUOTES);
                    $date = date('d/m/Y', strtotime(htmlspecialchars($row['date'])));
                    $location = htmlspecialchars($row['location'], ENT_QUOTES);
                    $authorized_roles = htmlspecialchars($row['role'], ENT_QUOTES);
                    $guest_count = htmlspecialchars($row['guest_count'], ENT_QUOTES);
                    $creator = htmlspecialchars($row['creator'], ENT_QUOTES);
                    echo "<div class='event-card'>";
                    echo "<h3>$title</h3>";
                    echo "<p class='text'>Type d'évènement : $event_type</p>";
                    echo "<p class='text'>Date : $date</p>";
                    echo "<p class='text'>Lieu : $location</p>";
                    echo "<p class='text'>Nombre de participants : $guest_count</p>";
                    echo "<p class='text'>(Roles autorisés) : $authorized_roles</p>";
                    echo "<p class='text'>$description</p>";
                    if ($role != 'Administrateur') {
                        echo "<button class='submit-button' onclick=\"window.location.href = '/events/book/book.php?usermail=$email&event=$eventID';\">Réserver une place</button>";
                    }
                    if ($role == 'Sportif') {
                        echo "<button class='submit-button' onclick=\"window.location.href = '/events/participate/participate.php?usermail=$email&event=$eventID';\">Concourir</button>";
                    }
                    echo "</div>";
                }
            } else {
                echo "<p class='text'>Aucun évènement trouvé à la date recherchée.</p>";
            }
            echo "</div>";
        }


        if (isset($_GET['title'])) {
            $title = $_GET['title'];
            echo "<h2>Évènement intitulé \"$title\"</h2>";
            if ($role == 'Administrateur') {
                $query = "SELECT * FROM event WHERE title = '$title'";
            } else {
                $query = "SELECT * FROM event WHERE title = '$title' AND role LIKE '%$role%'";
            }
            $result = mysqli_query($db, $query);
            echo "<div class='events-flex-container'>";
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $eventID = htmlspecialchars($row['id'], ENT_QUOTES);
                    $title = htmlspecialchars($row['title'], ENT_QUOTES);
                    $description = htmlspecialchars($row['description'], ENT_QUOTES);
                    $event_type = htmlspecialchars($row['event_type'], ENT_QUOTES);
                    $date = date('d/m/Y', strtotime(htmlspecialchars($row['date'])));
                    $location = htmlspecialchars($row['location'], ENT_QUOTES);
                    $authorized_roles = htmlspecialchars($row['role'], ENT_QUOTES);
                    $guest_count = htmlspecialchars($row['guest_count'], ENT_QUOTES);
                    $creator = htmlspecialchars($row['creator'], ENT_QUOTES);
                    echo "<div class='event-card'>";
                    echo "<h3>$title</h3>";
                    echo "<p class='text'>Type d'évènement : $event_type</p>";
                    echo "<p class='text'>Date : $date</p>";
                    echo "<p class='text'>Lieu : $location</p>";
                    echo "<p class='text'>Nombre de participants : $guest_count</p>";
                    echo "<p class='text'>(Roles autorisés) : $authorized_roles</p>";
                    echo "<p class='text'>$description</p>";
                    if ($role != 'Administrateur') {
                        echo "<button class='submit-button' onclick=\"window.location.href = '/events/book/book.php?usermail=$email&event=$eventID';\">Réserver une place</button>";
                    }
                    if ($role == 'Sportif') {
                        echo "<button class='submit-button' onclick=\"window.location.href = '/events/participate/participate.php?usermail=$email&event=$eventID';\">Concourir</button>";
                    }
                    echo "</div>";
                }
            } else {
                echo "<p class='text'>Aucun évènement n'est prévu à ce nom.</p>";
            }
            echo "</div>";
        } else {

        }
        echo "</div>";
    } else {
        echo "<p class='text'>Vous n'êtes pas autorisé à accéder à cette page.</p>";
        echo "<p class='text'>Redirection vers l'accueil dans 5 secondes...</p>";
        header("refresh:5; url=/");
        die();

    }
    ?>


    <!-- code de la page ici -->

    <?php include ($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php') ?>
</body>

</html>