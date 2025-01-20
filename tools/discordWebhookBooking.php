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

$url = "https://ptb.discord.com/api/webhooks/1251631249357803540/c-GCgeZZIG-gVzCxXJ77QhSrPhS2VbVHFKhSV7eqrK7UrKEPBc-0lXwI9FKDwiysgAg6";

$hookObject = json_encode([
    "content" => "## ✅ Nouvelle réservation à un évènement ! \n@here",
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

            // Field array of objects
            "fields" => [
                [
                    "name" => "Nom",
                    "value" => $name,
                    "inline" => true
                ],
                [
                    "name" => "Prénom",
                    "value" => $familyName,
                    "inline" => true
                ],
                [
                    "name" => "Email",
                    "value" => $email,
                    "inline" => true
                ],
                [
                    "name" => "Rôle",
                    "value" => $role,
                    "inline" => true
                ],
                [
                    "name" => "Évènement",
                    "value" => $eventTitle,
                    "inline" => true
                ],
                [
                    "name" => "Date",
                    "value" => $eventDate,
                    "inline" => true
                ],
                [
                    "name" => "Lieu",
                    "value" => $eventLocation,
                    "inline" => true
                ],
                [
                    "name" => "Type",
                    "value" => $eventType,
                    "inline" => true
                ],
                [
                    "name" => "Description",
                    "value" => $eventDescription,
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