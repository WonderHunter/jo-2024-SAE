<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tools/dbConnect.php';
session_start();

$userDataEncoded = isset($_COOKIE['userData']) ? $_COOKIE['userData'] : null;
if ($userDataEncoded) {
    $userData = json_decode($userDataEncoded, true); // 'true' pour obtenir un tableau associatif

    $email = $userData['email'];
    $name = $userData['name'];
    $familyName = $userData['familyName'];
    $role = $userData['role'];
}

if (isset($_GET['usermail'])) {
    $userEmail = $_GET['usermail'];
    $stmt = mysqli_prepare($db, "SELECT * FROM user WHERE mail = ?");
    mysqli_stmt_bind_param($stmt, "s", $userEmail);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $userEmail = $row["mail"];
        $userFirstName = $row["name"];
        $userFamilyName = $row["family_name"];
        $userRole = $row["role"];
    }

} else {
    echo "<p class='text' >Vous n'avez pas renseigné d'utilisateur à modifier.</p>";
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../../styles/main.css" />
    <link rel="stylesheet" href="../../styles/header.css" />
    <link rel="stylesheet" href="../../styles/footer.css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="icon" type="image/png" sizes="32x32"
        href="https://tickets.paris2024.org/obj/media/FR-Paris2024/specialLogos/favicons/favicon-32x32.png" />
    <script src="https://kit.fontawesome.com/f16a36bad3.js" crossorigin="anonymous"></script>
    <script>
        if (!document.cookie.includes("userData")) { window.location.href = '/account/login'; }
    </script>

    <title>Profil de l'utilisateur| Jeux Olympiques - Paris 2024</title>
</head>

<body>
    <?php include ($_SERVER['DOCUMENT_ROOT'] . '/views/header.php'); ?>
    <h1>Profil de l'utilisateur :</h1>
    <?php
    $role = $userData['role'];
    echo "<p class='text'>Rôle du cookie : $role</p>";
    $stmt = mysqli_prepare($db, "SELECT * FROM user WHERE mail = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $role = $row["role"];
        echo "<p class='text'>Rôle actuel : $role</p>";
    }
    if ($role != 'Administrateur') {
        echo "<p class='text'>Vous n'êtes pas autorisé à accéder à cette page.</p>";
        echo "<p class='text'>Redirection vers l'accueil dans 5 secondes...</p>";
        //remet à jour le cookie de l'utilisateur pour remettre son rôle à jour
        setcookie('userData', json_encode(array('email' => $email, 'name' => $name, 'familyName' => $familyName, 'role' => $role)), time() + 365 * 24 * 3600, '/');

        header("refresh:5; url=/");
        die();
    } else {
        echo "<div class='login-container' style='margin-top: 1rem;'>";
        echo "<img src='https://cdn-icons-png.flaticon.com/512/4139/4139948.png' alt='Avatar'>";
        echo "<h2>Mettre à jour ces informations</h2>";
        echo "<p>Modifiez les informations de ce profil.</p>";

        if (isset($_GET['success'])) {
            $userEditedMail = $_GET['usermail'];
            if ($_GET['success'] == 'true') {
                echo "<p class='text' style='color:green;'>✅ L'utilisateur $userEditedMail a bien été modifié.</p>";
            } else {
                echo "<p class='text' style='color:red'>⛔ Aucune modification n'a été effectuée pour $userEditedMail.</p>";
            }
        }
        echo "<form action='/admin/users/edit/edit.php' method='post'>";
        echo "<div class='form-group'>";
        echo "<label for='name'><span style='color:red;'><abbr title='Requis'>*</abbr></span> Prénom</label>";
        echo "<input type='text' id='name' name='name' value='$userFirstName'>";
        echo "</div>";
        echo "<div class='form-group'>";
        echo "<label for='family_name'><span style='color:red;'><abbr title='Requis'>*</abbr></span> Nom</label>";
        echo "<input type='text' id='family_name' name='family_name' value='$userFamilyName'>";
        echo "</div>";
        echo "<div class='form-group'>";
        echo "<label for='email'>Adresse mail</label><input type='email' id='email' name='email' style='cursor: not-allowed;' value='$userEmail' readonly>";
        echo "</div>";
        echo "<div class='form-group'>";
        echo "<label for='role'>Rôle actuel</label>";
        echo "<input type='text' id='role' name='role' style='cursor: not-allowed;' value='$userRole' readonly>";
        echo "</div>";
        echo "<div class='form-group'>";
        echo "<label for='role'><span style='color:red;'><abbr title='Requis'>*</abbr></span> Rôle à assigner</label>";
        echo "<select name='role' id='role'>";
        echo "<option>Administrateur</option>";
        echo "<option>Organisateur</option>";
        echo "<option>Sportif</option>";
        echo "<option>Spectateur</option>";
        echo "</select>";
        echo "<button type='submit' class='submit-button'><i class='fas fa-edit'></i>";
        echo "Mettre à jour ces informations</i></button>";
        echo "</div>";
        echo "</form>";
        echo "</div>";
    }
    ?>

    <?php include ($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php') ?>
</body>

</html>