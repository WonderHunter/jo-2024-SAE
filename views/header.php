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
?>

<nav class="menu-container">
    <input type="checkbox" aria-label="Toggle menu" />

    <span></span>
    <span></span>
    <span></span>

    <a href="<?php
    if (isset($_COOKIE['userData'])) {
        echo "/?res=login-succeeded";
    } else {
        echo "/";
    }
    ?>" class="menu-logo">
        <img src="https://i.imgur.com/RkmSo9Q.png" alt="Jeux Olympiques - Paris 2024" />
    </a>

    <div class="menu">
        <ul>
            <?php
            if (isset($_COOKIE['userData'])) {
                $role = $userData['role'];
                if ($role == 'Administrateur') {
                    echo "<li class='dropdown'>";
                    echo "<a href='/admin'>Panel d'administration</a>";
                    echo "<ul class='dropdown-content'>";
                    echo "<li><a href='/admin/users'>Gérer les utilisateurs</a></li>";

                    echo "</ul>";
                    echo "</li>";
                }
            }
            ?>

            <li class="dropdown">
                <a href="/events">Évènements</a>
                <ul class="dropdown-content">
                    <?php
                    if (isset($_COOKIE['userData'])) {
                        $role = $userData['role'];
                        if ($role == 'Administrateur') {
                            echo "<li><a href='/events/new'>Créer un nouvel évènement</a></li>";
                            echo "<li><a href='/events/list'>Rechercher un évènement</a></li>";
                        } else if ($role == 'Sportif') {
                            echo "<li><a href='/events/participate'>Participer à un évènement</a></li>";
                            echo "<li><a href='/events/list'>Rechercher un évènement</a></li>";
                        } else if ($role == "Organisateur") {
                            echo "<li><a href='/events/new'>Créer un nouvel évènement</a></li>";
                            echo "<li><a href='/events/list'>Rechercher un évènement</a></li>";
                        } else if ($role == "Membre") {
                            echo "<li><a href='/events/list'>Rechercher un évènement</a></li>";
                        }
                    } else {
                        echo "<li><a href='/events/list'>Rechercher un évènement</a></li>";
                    }

                    ?>
                </ul>
            </li>
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

        <ul>
            <li><?php
            if (isset($_COOKIE['userData'])) {
                echo "<a href='/tools/logout.php' title='Déconnexion'><i class='fas fa-sign-out-alt'></i></a>";
            }
            ?></li>
            <li>
                <?php
                if (isset($_COOKIE['userData'])) {
                    $userName = "" . $userData['name'] . " " . $userData['familyName'] . "";
                    echo "<a href='/account/profile'><img class='avatar' src='https://cdn-icons-png.flaticon.com/64/4139/4139948.png' title=" . $userName . "></a>";
                } else {
                    echo "<a href='/account/login'>Connexion</a>";
                }

                ?>

            </li>
        </ul>
    </div>
</nav>
<script src="https://kit.fontawesome.com/f16a36bad3.js" crossorigin="anonymous"></script>