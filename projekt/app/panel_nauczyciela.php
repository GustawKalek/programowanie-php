<?php
require_once "includes/auth.php";
require_once "includes/db.php";
wymagaj_roli("nauczyciel");

$nauczyciel_id = $_SESSION["user_id"];

// przedmioty nauczyciela z liczbą uczniów i ocen
$stmt = $db->prepare("SELECT p.id, p.nazwa,
	(SELECT COUNT(*) FROM uczniowie_przedmioty WHERE przedmiot_id = p.id) AS uczniow,
	(SELECT COUNT(*) FROM oceny WHERE przedmiot_id = p.id) AS ocen
	FROM przedmioty p
	WHERE p.nauczyciel_id = ?
	ORDER BY p.nazwa");
$stmt->bind_param("i", $nauczyciel_id);
$stmt->execute();
$przedmioty = $stmt->get_result();
$stmt->close();

$tytul = "Panel nauczyciela";
include "includes/naglowek.php";
?>
		<h2>Panel nauczyciela</h2>
		<p>Zalogowano jako: <?php echo htmlspecialchars($_SESSION["imie"] . " " . $_SESSION["nazwisko"]); ?></p>

		<h3>Moje przedmioty</h3>
		<?php
		if ($przedmioty->num_rows == 0) {
			echo "<p>Nie masz przypisanych przedmiotów.</p>";
		} else {
			echo "<div class=\"kafelki\">";
			while ($p = $przedmioty->fetch_assoc()) {
				echo "<div class=\"kafelek\">";
				echo "<h3>" . htmlspecialchars($p["nazwa"]) . "</h3>";
				echo "<p>Uczniów: " . $p["uczniow"] . "<br>Ocen: " . $p["ocen"] . "</p>";
				echo "<a href=\"oceny.php?przedmiot=" . $p["id"] . "\">Oceny</a> | ";
				echo "<a href=\"historia.php?przedmiot=" . $p["id"] . "\">Historia</a>";
				echo "</div>";
			}
			echo "</div>";
		}
		?>
<?php include "includes/stopka.php"; ?>
