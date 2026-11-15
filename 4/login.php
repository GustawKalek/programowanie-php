<?php
session_start();

// print_r($_POST);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$login = $_POST["login"] ?? "";
$password = $_POST["password"] ?? "";

if ($login === "a" && $password === "1") {
    if (empty($_SESSION["loggedIn"])) {
        session_regenerate_id(true);
        $_SESSION["loggedInUsers"] = ($_SESSION["loggedInUsers"] ?? 0) + 1;
        $_SESSION["user"] = $login;
        $_SESSION["loggedIn"] = true;
    }

    header("Location: panel.php");
    exit();
} else {
    echo "Nieprawidłowy login lub hasło";
    echo '<br><a href="index.php">Spróbuj ponownie</a>';
}
