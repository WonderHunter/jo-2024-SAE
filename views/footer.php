<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tools/dbConnect.php';

if (isset($_COOKIE['userData'])) {
    $userDataEncoded = $_COOKIE['userData'];
    $userData = json_decode($userDataEncoded, true); // 'true' pour obtenir un tableau associatif

    $email = $userData['email'];
    $name = $userData['name'];
    $familyName = $userData['familyName'];
    $role = $userData['role'];
}
?>

<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <h6>à propos</h6>
                <p class="text-justify">Ce site est le fruit d'une SAÉ en informatique, destiné à simuler des
                    fonctionnalités du site officiel des Jeux Olympiques de Paris 2024. Il a été conçu pour démontrer
                    nos
                    compétences en développement web et en gestion de données, tout en offrant une expérience
                    utilisateur interactive et informative.</p>
            </div>

            <div class="col-xs-6 col-md-3">
                <h6>Catégories</h6>
                <ul class="footer-links">
                    <li><a href="/">Accueil</a></li>
                    <li><?php
                    if (isset($_COOKIE['userData'])) {
                        $role = $userData['role'];
                        if ($role == 'Administrateur') {
                            echo "<a href='/admin'>Panel d'administration</a>";
                        }
                    }
                    ?></li>
                    <li><a href="/events">Évènements</a></li>
                    <?php
                    if (isset($_COOKIE['userData'])) {
                        $role = $userData['role'];
                        if ($role != 'Administrateur') {
                            echo "<li><a href='/account/profile/myevents'>Mes réservations</a></li>";
                        }
                        if ($role == 'Sportif') {
                            echo "<li><a href='/account/profile/myentries'>Mes participations</a></li>";
                        }
                    }
                    ?>
                </ul>
            </div>

            <div class="col-xs-6 col-md-3">
                <h6>Liens rapides</h6>
                <ul class="footer-links">
                    <li><a href="https://grond.iut-fbleau.fr/balocchi/SAE_web" target="_blank">Code source du site</a>
                    </li>
                    <li>
                        <?php if (isset($_COOKIE['userData'])) {
                            echo "<a href='/account/profile'>Mon profil</a>";
                        } else {
                            echo "<a href='/account/login'>Connexion</a>";
                        } ?>
                    </li>

                </ul>
            </div>
        </div>
        <hr>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-sm-6 col-xs-12">
                <p class="copyright-text">Copyright &copy; 2024
                    <a href="https://grond.iut-fbleau.fr/charpentj">Juliette</a>
                    <a> & </a>
                    <a href="https://grond.iut-fbleau.fr/balocchi">Loris</a>
                </p>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <ul>
                    <li><a><img src="https://wakatime.com/badge/user/26707066-9796-45af-a9b8-895a6e3ce3f8/project/3b2aa1b8-0154-4504-a0f3-895328adcbf0.svg"
                                alt="wakatime"> Temps passé sur la SAÉ.</a></li>
            </div>
        </div>
</footer>