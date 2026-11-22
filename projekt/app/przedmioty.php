<?php
require_once "includes/auth.php";
require_once "includes/db.php";
wymagaj_roli("admin");

// usuwanie przedmiotu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$id = (int)$_POST["id"];

	$stmt = $db->prepare("DELETE FROM przedmioty WHERE id = ?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->close();

	header("Location: przedmioty.php");
	exit;
}

// LEFT JOIN, bo przedmiot może nie mieć nauczyciela
$wynik = $db->query("SELECT p.id, p.nazwa, n.imie, n.nazwisko,
	(SELECT COUNT(*) FROM uczniowie_przedmioty WHERE przedmiot_id = p.id) AS uczniow
	FROM przedmioty p
	LEFT JOIN uzytkownicy n ON n.id = p.nauczyciel_id
	ORDER BY p.nazwa");

$tytul = "Przedmioty";
include "includes/naglowek.php";
?>
		<h2>Przedmioty</h2>
		<p><a href="przedmiot_form.php">Dodaj przedmiot</a></p>

		<table>
			<tr>
				<th>Nazwa</th>
				<th>Nauczyciel</th>
				<th>Uczniów</th>
				<th></th>
			</tr>
			<?php while ($p = $wynik->fetch_assoc()) { ?>
			<tr>
				<td><?php echo htmlspecialchars($p["nazwa"]); ?></td>
				<td><?php echo htmlspecialchars($p["imie"] . " " . $p["nazwisko"]); ?></td>
				<td><?php echo $p["uczniow"]; ?></td>
				<td>
					<a href="oceny.php?przedmiot=<?php echo $p["id"]; ?>">Oceny</a>
					<a href="przedmiot_form.php?id=<?php echo $p["id"]; ?>">Edytuj</a>
					<form method="post" class="w-linii" onsubmit="return confirm('Na pewno usunąć ten przedmiot?');">
						<input type="hidden" name="id" value="<?php echo $p["id"]; ?>">
						<button type="submit">Usuń</button>
					</form>
				</td>
			</tr>
			<?php } ?>
		</table>
<?php include "includes/stopka.php"; ?>
