<?php
require_once __DIR__ . "/auth.php";
?>
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $tytul; ?> - Dziennik lekcyjny</title>
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
	<header>
		<h1>Dziennik lekcyjny</h1>
		<nav>
			<a href="index.php">Strona główna</a>
			<a href="o-szkole.php">O szkole</a>
			<a href="kontakt.php">Kontakt</a>
			<a href="regulamin.php">Regulamin</a>
			<?php
			if (czy_zalogowany()) {
				if ($_SESSION["rola"] == "admin") {
					echo "<a href=\"nauczyciele.php\">Nauczyciele</a>";
				}
				if ($_SESSION["rola"] == "nauczyciel") {
					echo "<a href=\"panel_nauczyciela.php\">Panel nauczyciela</a>";
				}
				echo "<a href=\"wyloguj.php\">Wyloguj (" . htmlspecialchars($_SESSION["imie"]) . ")</a>";
			} else {
				echo "<a href=\"logowanie.php\">Logowanie</a>";
			}
			?>
		</nav>
	</header>
	<main>
