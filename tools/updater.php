<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tools/dbConnect.php';
session_start();

if (isset($_COOKIE['userData'])) {
    $userDataEncoded = $_COOKIE['userData'];
    $userData = json_decode($userDataEncoded, true);

    $email = $userData['email'];
    $oldEmail = $userData['email'];
    $name = $userData['name'];
    $familyName = $userData['familyName'];
    $role = $userData['role'];
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $familyName = htmlspecialchars($_POST["family_name"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);
    $hashedPassword = sha1($password);



    $updateUser = "UPDATE `user` SET `name`='$name',`family_name`='$familyName',`password`='$hashedPassword' WHERE mail = '$oldEmail'";
    $resultUpdateUser = mysqli_query($db, $updateUser);
    if ($resultUpdateUser) {
        setcookie("userData", "", time() - 3600, "/");
        $role = $userData['role'];
        $userData = array(
            "email" => $email,
            "name" => $name,
            "familyName" => $familyName,
            "role" => $role,
        );
        $userDataEncoded = json_encode($userData);
        setcookie("userData", $userDataEncoded, time() + 3600, "/");
        echo "<p class='text'>Utilisateur mis à jour avec succès. </p>";
        header("Location: /account/profile?result=updatesuccess");
    } else {
        echo "<p class='text'>Erreur lors de l'exécution de la requête.</p>";
        header("Location: /account/profile?result=updatefailed");
    }
}

?>