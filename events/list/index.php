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
  <script src="/scripts/redirectToSelected.js" defer></script>
  <title>List des évènements | Jeux Olympiques - Paris 2024</title>
</head>

<body>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/views/header.php' ?>

  <?php
  if (!isset($_COOKIE['userData'])) {
    echo "<p class='text'>Vous n'êtes pas autorisé à accéder à cette page.</p>";
    echo "<p class='text'>Redirection vers l'accueil dans 5 secondes...</p>";
    header("refresh:5; url=/");
    include $_SERVER['DOCUMENT_ROOT'] . '/views/footer.php';
    die();
  }
  ?>

  <div class="event-container">
    <img src="https://cdn-icons-png.flaticon.com/512/2538/2538566.png" alt="Avatar">
    <h2 class="event-title">Afficher les évènements</h2>
    <p class="event-subtitle">Remplissez le formulaire suivant afin de trier les évènements à votre guise.</p>

    <div class="form-group">
      <label for="eventName">Nom de l'évènement</label>
      <select name="eventName" id="eventName">
        <?php
        $query = "SELECT DISTINCT title FROM event";
        $result = mysqli_query($db, $query);

        while ($row = mysqli_fetch_assoc($result)) {
          $title = htmlspecialchars($row['title'], ENT_QUOTES);
          echo "<option value='" . $title . "'>" . $title . "</option>";
        }
        ?>
      </select>
      <button type="submit" onclick="redirectToSelectedTitle()"><i class="fas fa-search"></i> Rechercher</button>
    </div>

    <div class="form-group">
      <label for="location">Sélectionner un lieu :</label>
      <select name="location" id="location">
        <?php
        $query = "SELECT DISTINCT location FROM event";
        $result = mysqli_query($db, $query);

        while ($row = mysqli_fetch_assoc($result)) {
          $location = htmlspecialchars($row['location'], ENT_QUOTES);
          echo "<option value='" . $location . "'>" . $location . "</option>";
        }
        ?>
      </select>
      <button type="submit" onclick="redirectToSelectedLocation()"><i class="fas fa-search"></i> Rechercher</button>
    </div>
    <div class="form-group">
      <div class="event-date">
        <label for="eventDate">Sélectionner une date :</label>
        <select name="eventDate" id="eventDate">
          <?php
          $query = "SELECT DISTINCT date FROM event ORDER BY date ASC";
          $result = mysqli_query($db, $query);

          while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['date'] . "'>" . $row['date'] . "</option>";
          }
          ?>
        </select>
        <button type="submit" onclick="redirectToSelectedDate()"><i class="fas fa-search"></i> Rechercher</button>
      </div>
    </div>


    <!-- code de la page ici -->

  </div>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/views/footer.php' ?>
</body>

</html>