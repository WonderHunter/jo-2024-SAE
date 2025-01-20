<?php
$db = mysqli_connect("dwarves.iut-fbleau.fr", "charpentj", "PommePatate", "charpentj");
if (!$db) {
    echo "<script>alert('Echec de la connexion à la BDD.')</script>";
    die();
}
;
?>