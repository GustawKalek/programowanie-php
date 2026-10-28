<?php
require_once "includes/auth.php";
wymagaj_roli("nauczyciel");

$tytul = "Panel nauczyciela";
include "includes/naglowek.php";
?>
		<h2>Panel nauczyciela</h2>
		<p>Zalogowano jako: <?php echo htmlspecialchars($_SESSION["imie"] . " " . $_SESSION["nazwisko"]); ?></p>
<?php include "includes/stopka.php"; ?>
