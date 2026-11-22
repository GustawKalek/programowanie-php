<?php
require_once "includes/auth.php";
require_once "includes/db.php";
wymagaj_roli("admin");

// usuwanie ucznia, jego oceny usuwa baza (ON DELETE CASCADE)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$id = (int)$_POST["id"];

	$stmt = $db->prepare("DELETE FROM uzytkownicy WHERE id = ? AND rola = 'uczen'");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->close();

	header("Location: uczniowie.php");
	exit;
}

$wynik = $db->query("SELECT id, imie, nazwisko, email, klasa FROM uzytkownicy WHERE rola = 'uczen' ORDER BY klasa, nazwisko, imie");

$tytul = "Uczniowie";
include "includes/naglowek.php";
?>
		<h2>Uczniowie</h2>
		<p><a href="uczen_form.php">Dodaj ucznia</a></p>

		<table>
			<tr>
				<th>Klasa</th>
				<th>Imię i nazwisko</th>
				<th>Login</th>
				<th></th>
			</tr>
			<?php while ($u = $wynik->fetch_assoc()) { ?>
			<tr>
				<td><?php echo htmlspecialchars($u["klasa"]); ?></td>
				<td><?php echo htmlspecialchars($u["imie"] . " " . $u["nazwisko"]); ?></td>
				<td><?php echo htmlspecialchars($u["email"]); ?></td>
				<td>
					<a href="uczen_form.php?id=<?php echo $u["id"]; ?>">Edytuj</a>
					<form method="post" class="w-linii" onsubmit="return confirm('Na pewno usunąć tego ucznia?');">
						<input type="hidden" name="id" value="<?php echo $u["id"]; ?>">
						<button type="submit">Usuń</button>
					</form>
				</td>
			</tr>
			<?php } ?>
		</table>
<?php include "includes/stopka.php"; ?>
