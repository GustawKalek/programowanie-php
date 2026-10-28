<?php
require_once "includes/auth.php";
require_once "includes/db.php";
wymagaj_roli("admin");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$id = (int)$_POST["id"];

	$stmt = $db->prepare("DELETE FROM uzytkownicy WHERE id = ? AND rola = 'nauczyciel'");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->close();

	header("Location: nauczyciele.php");
	exit;
}

$wynik = $db->query("SELECT id, imie, nazwisko, email FROM uzytkownicy WHERE rola = 'nauczyciel' ORDER BY nazwisko, imie");

$tytul = "Nauczyciele";
include "includes/naglowek.php";
?>
		<h2>Nauczyciele</h2>
		<p><a href="nauczyciel_form.php">Dodaj nauczyciela</a></p>

		<table>
			<tr>
				<th>Imię i nazwisko</th>
				<th>Login</th>
				<th></th>
			</tr>
			<?php while ($n = $wynik->fetch_assoc()) { ?>
			<tr>
				<td><?php echo htmlspecialchars($n["imie"] . " " . $n["nazwisko"]); ?></td>
				<td><?php echo htmlspecialchars($n["email"]); ?></td>
				<td>
					<a href="nauczyciel_form.php?id=<?php echo $n["id"]; ?>">Edytuj</a>
					<form method="post" class="w-linii" onsubmit="return confirm('Na pewno usunąć tego nauczyciela?');">
						<input type="hidden" name="id" value="<?php echo $n["id"]; ?>">
						<button type="submit">Usuń</button>
					</form>
				</td>
			</tr>
			<?php } ?>
		</table>
<?php include "includes/stopka.php"; ?>
