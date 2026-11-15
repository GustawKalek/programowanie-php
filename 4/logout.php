<?php
session_start();

if (!empty($_SESSION["loggedIn"])) {
    $_SESSION["loggedInUsers"]--;
}

$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $cookieParams = session_get_cookie_params();
    setcookie(
        session_name(),
        "",
        time() - 42000,
        $cookieParams["path"],
        $cookieParams["domain"],
        $cookieParams["secure"],
        $cookieParams["httponly"]
    );
}

session_destroy();

header("Location: index.php");
exit();
