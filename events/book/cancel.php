<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tools/dbConnect.php';
session_start();

if (isset($_COOKIE['userData'])) {
    $userData = json_decode($_COOKIE['userData'], true);

    $email = $userData['email'];
    $name = $userData['name'];
    $familyName = $userData['familyName'];
    $role = $userData['role'];
}

if (isset($_GET['usermail']) && isset($_GET['id'])) {
    $userEmail = $_GET['usermail'];
    $eventId = $_GET['id'];

    $stmt = mysqli_prepare($db, "SELECT * FROM booking WHERE id = ? AND mail = ?");
    mysqli_stmt_bind_param($stmt, "is", $eventId, $userEmail);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $eventDetails = mysqli_fetch_assoc($result);

    if ($eventDetails) {
        $eventTitle = $eventDetails["title"];
        $eventDescription = $eventDetails["description"];
        $eventType = $eventDetails["event_type"];
        $eventDate = $eventDetails["date"];
        $eventLocation = $eventDetails["location"];

        echo "<p class='text'>Vous vous apprêtez à annuler votre inscription à l'évènement suivant : </p>";
        echo "<p class='text'>Nom de l'évènement : $eventTitle</p>";
        echo "<p class='text'>Date de l'évènement : $eventDate</p>";
        echo "<p class='text'>Lieu de l'évènement : $eventLocation</p>";
        echo "<p class='text'>Discipline de l'évènement : $eventType</p>";
        echo "<p class='text'>Description de l'évènement : $eventDescription</p>";
        echo "<p class='text'>Adresse mail de l'utilisateur : $userEmail</p>";

        $stmt = mysqli_prepare($db, "DELETE FROM booking WHERE id = ? AND mail = ?");
        mysqli_stmt_bind_param($stmt, "is", $eventId, $userEmail);
        if (mysqli_stmt_execute($stmt)) {


            echo "<p class='text'>Votre annulation à l'évènement $eventTitle a bien été prise en compte.</p>";

            $stmt = mysqli_prepare($db, "UPDATE event SET guest_count = guest_count - 1 WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $eventId);
            if (mysqli_stmt_execute($stmt)) {
                echo "<p class='text'>Le nombre de participants a été mis à jour.</p>";
                include $_SERVER['DOCUMENT_ROOT'] . '/tools/discordWebhookBooking.php';
                header("Location: /account/profile/myevents?res=cancellation-succeeded&eventtitle=$eventTitle");
            } else {
                echo "<p class='text'>Erreur lors de la mise à jour du nombre de participants.</p>";
            }
        } else {
            echo "<p class='text'>Une erreur est survenue lors de votre annulation à l'évènement. Erreur : " . mysqli_error($db) . "</p>";
            header("Location: /account/profile/myevents?res=cancellation-failed");
        }
    }
}
?>