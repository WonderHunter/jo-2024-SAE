<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/tools/dbConnect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);
}
if (isset($_COOKIE['userData'])) {
    header("Location: /account/profile");
}
$hashedPassword = sha1($password);
$stmt = mysqli_prepare($db, "SELECT * FROM user WHERE mail = ? AND password = ?");
mysqli_stmt_bind_param($stmt, 'ss', $email, $hashedPassword);
$result = mysqli_stmt_execute($stmt);
$result = $stmt->get_result();


if (!$result) {
    die('Erreur de requête : ' . mysqli_error($db));
} else {
    if (mysqli_num_rows($result) == 0) {
        header("Location: /account/login?res=login-failed");
        die();
    } else {
        // Requête pour récupérer les informations de l'utilisateur
        $query = "SELECT name, family_name, role FROM user WHERE mail = '$email'";
        $result = mysqli_query($db, $query);

        if (!$result) {
            die('Erreur de requête : ' . mysqli_error($db));
        }

        // Récupération des données
        if ($row = mysqli_fetch_assoc($result)) {
            $nameFetched = $row['name'];
            $familyNameFetched = $row['family_name'];
            $roleFetched = $row['role'];
        } else {
            echo "Aucun utilisateur trouvé avec cet email.";
        }
        // Mise en forme dans un format .JSON et création du cookie.
        $userData = array(
            "email" => $email,
            "name" => $nameFetched,
            "familyName" => $familyNameFetched,
            "role" => $roleFetched
        );
        $userDataEncoded = json_encode($userData);
        setcookie("userData", $userDataEncoded, time() + 3600, "/");

        // Utilistation d'un Webhook Discord pour notifier l'administrateur de la 
        //connexion des utilisateurs [fork de : https://stackoverflow.com/a/73547248]

        include ($_SERVER['DOCUMENT_ROOT'] . '/tools/discordWebhookLogin.php');

        header("Location: /?res=login-succeeded");
        die();
    }
}
?>