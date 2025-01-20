<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tools/dbConnect.php';
session_start();

if (isset($_COOKIE['userData'])) {
  $userData = json_decode($_COOKIE['userData'], true);

  $email = $userData['email'];
  $name = $userData['name'];
  $familyName = $userData['familyName'];
  $role = $userData['role'];
} else {
  echo "<p class='text'>Vous n'êtes pas autorisé à accéder à cette page.</p>";
  echo "<p class='text'>Redirection vers l'accueil dans 5 secondes...</p>";
  header("refresh:5; url=/");
  die();

}


if (isset($_GET['usermail']) && isset($_GET['id'])) {
  $userEmail = $_GET['usermail'];
  $eventId = $_GET['id'];



  $stmt = mysqli_prepare($db, "SELECT * FROM event WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "i", $eventId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $eventDetails = mysqli_fetch_assoc($result);

  if ($eventDetails) {
    $eventTitle = $eventDetails["title"];
    $eventDescription = $eventDetails["description"];
    $eventType = $eventDetails["event_type"];
    $eventDate = $eventDetails["date"];
    $eventLocation = $eventDetails["location"];

    echo "<p class='text'>Vous vous apprêtez à participer à l'évènement suivant : </p>";
    echo "<p class='text'>Nom de l'évènement : $eventTitle</p>";
    echo "<p class='text'>Date de l'évènement : $eventDate</p>";
    echo "<p class='text'>Lieu de l'évènement : $eventLocation</p>";
    echo "<p class='text'>Discipline de l'évènement : $eventType</p>";
    echo "<p class='text'>Description de l'évènement : $eventDescription</p>";
    echo "<p class='text'>Adresse mail de l'utilisateur : $userEmail</p>";

    $stmt = mysqli_prepare($db, "INSERT INTO event_entries (id, mail, title, description, event_type, date, location) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "issssss", $eventId, $userEmail, $eventTitle, $eventDescription, $eventType, $eventDate, $eventLocation);
    if (mysqli_stmt_execute($stmt)) {
      echo "<p class='text'>Votre inscription à l'évènement $eventTitle a bien été prise en compte.</p>";
      header("Location: /account/profile/myentries?res=entry-succeeded");
      die();
    } else {
      echo "<p class='text'>Une erreur est survenue lors de votre inscription à l'évènement. Erreur : " . mysqli_error($db) . "</p>";
      header("Location: /account/profile/myentries?res=entry-failed");
      die();
    }
  } else {
    echo "<p class='text'>Évènement introuvable.</p>";
  }
}
?>