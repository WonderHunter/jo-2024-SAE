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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userName = htmlspecialchars($_POST["name"]);
    $userFamilyName = htmlspecialchars($_POST["family_name"]);
    $userEmail = htmlspecialchars($_POST["email"]);
    $userPassword = htmlspecialchars($_POST["password"]);
    $userUserRole = htmlspecialchars($_POST["role"]);
    $hashedPassword = sha1($userPassword);
}



$query = "SELECT mail FROM user WHERE mail = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "s", $userEmail);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("Erreur lors de l'exécution de la requête.");
}

$row = mysqli_fetch_assoc($result);

if ($row) {
    $addUser = "INSERT INTO `user`(`mail`, `name`, `family_name`, `role`, `password`) VALUES (?, ?, ?, ?, ?)";
    $stmtAddUser = mysqli_prepare($db, $addUser);
    mysqli_stmt_bind_param($stmtAddUser, "sssss", $userEmail, $userName, $userFamilyName, $userUserRole, $hashedPassword);
    $resultAddUser = mysqli_stmt_execute($stmtAddUser);
    $addUser = "INSERT INTO `user`(`mail`, `name`, `family_name`, `role`, `password`) VALUES ('$userEmail', '$userName', '$userFamilyName', '$userUserRole', '$hashedPassword')";

    $resultAddUser = mysqli_query($db, $addUser);
    if (!$resultAddUser) {
        echo "<p class='text'>Erreur lors de l'exécution de la requête.</p>";
    } else {
        if (mysqli_affected_rows($db) > 0) {
            echo "<p class='text'>Utilisateur créé avec succès. </p>";

            echo "<p class='text'>Prénom : " . $userName . "</p>";
            echo "<p class='text'>Nom : " . $userFamilyName . "</p>";
            echo "<p class='text'>Adresse mail : " . $userEmail . "</p>";
            echo "<p class='text'>Mot de passe : " . $userPassword . "</p>";
            echo "<p class='text'>Mot de passe hashé : " . $hashedPassword . "</p>";
            echo "<p class='text'>Rôle : " . $userUserRole . "</p>";
            include ($_SERVER['DOCUMENT_ROOT'] . '/tools/discordWebhookRegister.php');
            header("Location: /admin/users/?newuser=$userEmail");
            die();
        } else {
            echo "<p class='text'>Aucun utilisateur n\'a été ajouté.</p>";
        }
    }
}
?>