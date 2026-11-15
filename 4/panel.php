<?php
session_start();

if (empty($_SESSION["loggedIn"]) || empty($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

$safeUser = htmlspecialchars($_SESSION["user"], ENT_QUOTES, "UTF-8");
$_SESSION["loggedInUsers"] = $_SESSION["loggedInUsers"] ?? 1;
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel użytkownika</title>
</head>

<body>
    <h1>Panel użytkownika</h1>

    <h2>Witaj, <?= $safeUser ?>!</h2>

    <p>Logowanie zakończone powodzeniem</p>
    <p>Liczba zalogowanych użytkowników: <?= $_SESSION["loggedInUsers"] ?></p>
    <a href="logout.php">Wyloguj</a>
</body>

</html>