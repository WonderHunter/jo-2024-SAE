<?php
session_start();

if (isset($_COOKIE['userData'])) {
    unset($_COOKIE['userData']);
    setcookie('userData', '', time() - 3600, '/');
}

$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

header("Location: /account/login");
exit();
?>