<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tools/dbConnect.php';

if (isset($_COOKIE['userData'])) {
    $userDataEncoded = $_COOKIE['userData'];
    $userData = json_decode($userDataEncoded, true);

    $email = $userData['email'];
    $name = $userData['name'];
    $familyName = $userData['familyName'];
    $role = $userData['role'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userName = htmlspecialchars($_POST["name"]);
    $userFamilyName = htmlspecialchars($_POST["family_name"]);
    $userEmail = htmlspecialchars($_POST["email"]);
    $userUserRole = htmlspecialchars($_POST["role"]);

}


$stmt = $db->prepare("UPDATE `user` SET `name`=?, `family_name`=?, `role`=? WHERE `mail`=?");
$stmt->bind_param("ssss", $userName, $userFamilyName, $userUserRole, $userEmail);
$resultEditUser = $stmt->execute();

if (!$resultEditUser) {
    echo "<p class='text'>Erreur lors de l'exécution de la requête.</p>";
} else {
    if ($stmt->affected_rows > 0) {
        echo "<p class='text'>L'utilisateur a bien été modifié.</p>";

        echo "<p class='text'>Prénom : " . $userName . "</p>";
        echo "<p class='text'>Nom : " . $userFamilyName . "</p>";
        echo "<p class='text'>Rôle : " . $userUserRole . "</p>";
        $stmt->close();
        header("Location: /admin/users/edit?usermail=$userEmail&success=true");
        die();
    } else {
        echo "<p class='text'>Aucune modification n'a été effectuée.</p>";
        $stmt->close();
        header("Location: /admin/users/edit?usermail=$userEmail&success=false");
        die();
    }
}


?>