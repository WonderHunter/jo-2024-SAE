<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tools/dbConnect.php';

include ($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $familyName = htmlspecialchars($_POST["family_name"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);
    $codeRole = htmlspecialchars($_POST["code-role"]);
}

$hashedPassword = sha1($password);

$query = "SELECT mail FROM user WHERE mail = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("Erreur lors de l'exécution de la requête.");
}

$row = mysqli_fetch_assoc($result);

if ($row) {
    die("<p>Un utilisateur avec cette adresse mail existe déjà.</p>");
}

if ($codeRole == "M25QP") {
    $role = "Administrateur";
} else if ($codeRole == "TF53K") {
    $role = "Sportif";
} else if ($codeRole == "VJC6V") {
    $role = "Organisateur";
} else {
    $role = "Spectateur";
}

$addUser = "INSERT INTO `user`(`mail`, `name`, `family_name`, `role`, `password`) VALUES ('$email', '$name', '$familyName', '$role', '$hashedPassword')";

$resultAddUser = mysqli_query($db, $addUser);
if (!$resultAddUser) {
    echo "<p class='text'>Erreur lors de l'exécution de la requête.</p>";
} else {
    if (mysqli_affected_rows($db) > 0) {
        echo "<p class='text'>Utilisateur créé avec succès. </p>";

        $userData = array(
            "email" => $email,
            "name" => $name,
            "familyName" => $familyName,
            "role" => $role,
        );
        $userDataEncoded = json_encode($userData);
        setcookie("userData", $userDataEncoded, time() + 3600, "/");

        echo "<p class='text'>Cookie créé avec succès. Contenu du cookie :</p>";
        echo "<p class='text'>" . $userDataEncoded . "</p>";

        echo "<p class='text'>Prénom : " . $name . "</p>";
        echo "<p class='text'>Nom : " . $familyName . "</p>";
        echo "<p class='text'>Adresse mail : " . $email . "</p>";
        echo "<p class='text'>Mot de passe : " . $password . "</p>";
        echo "<p class='text'>Mot de passe hashé : " . $hashedPassword . "</p>";
        echo "<p class='text'>Rôle : " . $role . "</p>";
        include ($_SERVER['DOCUMENT_ROOT'] . '/tools/discordWebhookRegister.php');
        header("Location: /?res=login-succeeded");
        die();
    } else {
        echo "<p class='text'>Aucun utilisateur n\'a été ajouté.</p>";
    }
}

include ($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
?>