<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tools/dbConnect.php';
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventName = $db->real_escape_string($_POST['eventName']);
    $eventDate = $db->real_escape_string($_POST['eventDate']);
    $eventLocation = $db->real_escape_string($_POST['eventLocation']);
    $eventDiscipline = $db->real_escape_string($_POST['eventDiscipline']);
    $eventDescription = $db->real_escape_string($_POST['eventDescription']);
    $eventRoles = isset($_POST['eventRole']) ? $_POST['eventRole'] : [];

    $rolesText = implode(', ', $eventRoles);

    if (isset($_COOKIE['userData'])) {
        $userDataEncoded = $_COOKIE['userData'];
        $userData = json_decode($userDataEncoded, true);
        $email = $userData['email'];
        $name = $userData['name'];
        $familyName = $userData['familyName'];
        $role = $userData['role'];
    }
    echo "<p class='text'>Nom de l'évènement : $eventName</p>";
    echo "<p class='text'>Date de l'évènement : $eventDate</p>";
    echo "<p class='text'>Lieu de l'évènement : $eventLocation</p>";
    echo "<p class='text'>Discipline de l'évènement : $eventDiscipline</p>";
    echo "<p class='text'>Description de l'évènement : $eventDescription</p>";
    echo "<p class='text'>Rôles de l'évènement : $rolesText</p>";
    echo "<p class='text'>Créateur de l'évènement : $email</p>";

    $stmt = mysqli_prepare($db, "INSERT INTO `event` (title, description, event_type, date, location, role, guest_count, creator) VALUES (?, ?, ?, ?, ?, ?, 0, ?)");
    mysqli_stmt_bind_param($stmt, 'sssssss', $eventName, $eventDescription, $eventDiscipline, $eventDate, $eventLocation, $rolesText, $email);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        echo "<p class='text'>L'évènement a bien été créé.</p>";
        include ($_SERVER['DOCUMENT_ROOT'] . '/tools/discordWebhookNewEvent.php');
        header("Location: /events/new?result=event-creation-succeded");
    } else {
        echo "<p class='text'>Une erreur est survenue lors de la création de l'évènement. Erreur : " . mysqli_error($db) . "</p>";
        header("Location: /events/new?result=event-creation-failed");
    }
} else {
    echo "<p class='text'>Veuillez remplir tous les champs du formulaire.</p>";
}

?>