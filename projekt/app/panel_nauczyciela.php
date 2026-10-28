<?php
require_once "includes/auth.php";
require_once "includes/db.php";
wymagaj_roli("nauczyciel");

$nauczyciel_id = $_SESSION["user_id"];

$stmt = $db->prepare("SELECT id, nazwa FROM przedmioty WHERE nauczyciel_id = ? ORDER BY nazwa");
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
			echo "<ul>";
			while ($p = $przedmioty->fetch_assoc()) {
				echo "<li><a href=\"oceny.php?przedmiot=" . $p["id"] . "\">" . htmlspecialchars($p["nazwa"]) . "</a></li>";
			}
			echo "</ul>";
		}
		?>
<?php include "includes/stopka.php"; ?>
