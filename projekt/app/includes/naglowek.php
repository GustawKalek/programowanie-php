<?php
require_once __DIR__ . "/auth.php";

// link w menu, aktualna strona dostaje klasę aktywny
function link_menu($plik, $tekst) {
	$klasa = "";
	if (basename($_SERVER["SCRIPT_NAME"]) == $plik) {
		$klasa = " class=\"aktywny\"";
	}
	echo "<a href=\"" . $plik . "\"" . $klasa . ">" . $tekst . "</a>";
}
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
			<?php
			link_menu("index.php", "Strona główna");
			link_menu("o-szkole.php", "O szkole");
			link_menu("kontakt.php", "Kontakt");
			link_menu("regulamin.php", "Regulamin");

			// dalsza część menu zależy od roli
			if (czy_zalogowany()) {
				if ($_SESSION["rola"] == "admin") {
					link_menu("nauczyciele.php", "Nauczyciele");
					link_menu("uczniowie.php", "Uczniowie");
					link_menu("przedmioty.php", "Przedmioty");
					link_menu("historia.php", "Historia ocen");
				}
				if ($_SESSION["rola"] == "nauczyciel") {
					link_menu("panel_nauczyciela.php", "Panel nauczyciela");
					link_menu("historia.php", "Historia ocen");
				}
				if ($_SESSION["rola"] == "uczen") {
					link_menu("moje_oceny.php", "Moje oceny");
					link_menu("historia.php", "Historia ocen");
				}
				link_menu("wyloguj.php", "Wyloguj (" . htmlspecialchars($_SESSION["imie"]) . ")");
			} else {
				link_menu("logowanie.php", "Logowanie");
			}
			?>
		</nav>
	</header>
	<main>
