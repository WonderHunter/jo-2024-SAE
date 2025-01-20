<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tools/dbConnect.php';
session_start();

if (isset($_COOKIE['userData'])) {
    $userDataEncoded = $_COOKIE['userData'];
    $userData = json_decode($userDataEncoded, true);

    $email = $userData['email'];
    $name = $userData['name'];
    $familyName = $userData['familyName'];
    $role = $userData['role'];
}
?>

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/views/header.php') ?>=
<!-- code de la page ici -->

<?php
// script php ici
if (isset($_GET['oldemail'])) {

    $userEmail = htmlspecialchars($_GET['oldemail']);
    $stmt = mysqli_prepare($db, "DELETE FROM user WHERE mail=?");
    mysqli_stmt_bind_param($stmt, 's', $userEmail);
    $result = mysqli_stmt_execute($stmt);
    echo "<p class='text'>L'utilisateur " . $userEmail . " a bien été supprimé.</p>";
    include ($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
    header("Location: /admin/users/?res=$userEmail");
    die();
} else {
    include ($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
    header("Location: /admin/users/?res=NONE");
    die();
}

?>