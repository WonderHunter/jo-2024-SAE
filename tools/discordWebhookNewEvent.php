<?php
// Code fork depuis https://stackoverflow.com/a/51748785

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

$url = "https://discord.com/api/webhooks/1251196052133118013/euJIbUIfmdXK0MJsS6WoEDLm2ipPV_efb1gLOeUaP3IZNIM4MRdqu2RuOWbVF_09d2ty";

$hookObject = json_encode([
    "content" => "## ✅ Nouvel évènement créé sur le site des Jeux Olympiques de Paris 2024 ! \n@here",
    "username" => "Jeux Olympiques - Paris 2024",
    "avatar_url" => "https://i.imgur.com/gg5xPa1.png",
    "tts" => false,
    "embeds" => [
        [
            "title" => "Jeux Olympiques - Paris 2024",
            "type" => "rich",
            "description" => "",
            "url" => "https://but.lbalocchi.fr/",
            "timestamp" => date('c', time()),
            "color" => hexdec("F4B400"),
            "footer" => [
                "text" => "© Juliette & Loris - 2024",
                "icon_url" => "https://tickets.paris2024.org/obj/media/FR-Paris2024/specialLogos/favicons/favicon-32x32.png"
            ],
            "image" => [
                "url" => "https://www.fromagersdefrance.com/wp-content/uploads/2023/03/1200px-Logo_JO_dete_-_Paris_2024.svg__0.png"
            ],
            "author" => [
                "name" => "Juliette & Loris",
                "url" => "https://stackoverflow.com/a/51748785",
            ],
            "fields" => [
                [
                    "name" => "Nom de l'évènement",
                    "value" => $eventName,
                    "inline" => false
                ],
                [
                    "name" => "Discipline",
                    "value" => $eventDiscipline,
                    "inline" => true
                ],
                [
                    "name" => "Date de l'évènement",
                    "value" => $eventDate,
                    "inline" => true
                ],
                [
                    "name" => "Lieu de l'évènement",
                    "value" => $eventLocation,
                    "inline" => true
                ],
                [
                    "name" => "Description de l'évènement",
                    "value" => $eventDescription,
                    "inline" => true
                ],
                [
                    "name" => "Rôles pouvant s'inscrire",
                    "value" => $rolesText,
                    "inline" => true
                ],
                [
                    "name" => "Créateur de l'évènement",
                    "value" => $name . " " . $familyName . " (" . $role . ")\n" . $email,
                    "inline" => true
                ]
            ]
        ]
    ]

], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $hookObject,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json"
    ]
]);
$response = curl_exec($ch);
curl_close($ch);
?>