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
    <link rel="stylesheet" href="../styles/main.css" />
    <link rel="stylesheet" href="../styles/header.css" />
    <link rel="stylesheet" href="../styles/footer.css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="icon" type="image/png" sizes="32x32"
        href="https://tickets.paris2024.org/obj/media/FR-Paris2024/specialLogos/favicons/favicon-32x32.png" />
    <title>Évènements | Jeux Olympiques - Paris 2024</title>
</head>

<body>
    <?php include ($_SERVER['DOCUMENT_ROOT'] . '/views/header.php') ?>
    <!-- code de la page ici -->
    <h1>Évènements</h1>

    <?php
    echo '<button class="new-event" onclick="window.location.href = \'/events/list\';">Rechercher un évènement</button>';
    if ($role == 'Organisateur') {
        echo '<button class="new-event" onclick="window.location.href = \'/events/new\';">Créer un évènement</button>';
    }

    $query = "SELECT * FROM event";

    if (isset($_GET['sort'])) {
        $sort = $_GET['sort'];
        switch ($sort) {
            case 'date':
                $query .= " ORDER BY date";
                break;
            case 'location':
                $query .= " ORDER BY location";
                break;
            case 'discipline':
                $query .= " ORDER BY event_type";
                break;
            default:
                break;
        }
    }

    // Menu déroulant pour sélectionner le tri
    echo "<form method='GET' action='' class='order-by'>";
    echo "<label for='sort' class='text'>Trier par:</label>";
    echo "<select name='sort' id='sort' onchange='this.form.submit()'>";
    echo "<option value=''>Aucun</option>";
    echo "<option value='date' " . ($sort == 'date' ? 'selected' : '') . ">Date</option>";
    echo "<option value='location' " . ($sort == 'location' ? 'selected' : '') . ">Lieu</option>";
    echo "<option value='discipline' " . ($sort == 'discipline' ? 'selected' : '') . ">Discipline</option>";
    echo "</select>";
    echo "</form>";
    $result = mysqli_query($db, $query);
    echo "<div class='events-flex-container'>";
    echo "<div class='scrollable'>";
    echo "<table class='event-table'>";
    echo "<thead>";  // En-tête du tableau
    echo "<tr>";
    echo "<th class='event-table-header'>Titre</th>";
    echo "<th class='event-table-header'>Description</th>";
    echo "<th class='event-table-header'>Discipline</th>";
    echo "<th class='event-table-header'>Date</th>";
    echo "<th class='event-table-header'>Lieu</th>";
    echo "<th class='event-table-header'>Action</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    while ($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td class='event-table-data'>" . htmlspecialchars($row['title']) . "</td>";
        echo "<td class='event-table-data'>" . htmlspecialchars($row['description']) . "</td>";
        echo "<td class='event-table-data'>" . htmlspecialchars($row['event_type']) . "</td>";
        echo "<td class='event-table-data'>" . date('d/m/Y', strtotime($row['date'])) . "</td>";
        echo "<td class='event-table-data'>" . htmlspecialchars($row['location']) . "</td>";
        if (isset($_COOKIE['userData'])) {
            if ($role == 'Sportif') {
                echo "<td class='event-table-data'><button class='submit-button' onclick='window.location.href=\"/events/participate/participate.php?usermail=$email&id=" . $row['id'] . "\"'>Participer</button></td>";
            }
        }
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo "</div>";

    ?>

    <?php include ($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php') ?>


</body>

</html>