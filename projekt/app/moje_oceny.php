<?php
require_once "includes/auth.php";
require_once "includes/db.php";
wymagaj_roli("uczen");

$uczen_id = $_SESSION["user_id"];

// przedmioty, na które uczeń jest zapisany
$stmt = $db->prepare("SELECT p.id, p.nazwa, n.imie, n.nazwisko FROM uczniowie_przedmioty up
	JOIN przedmioty p ON p.id = up.przedmiot_id
	LEFT JOIN uzytkownicy n ON n.id = p.nauczyciel_id
	WHERE up.uczen_id = ?
	ORDER BY p.nazwa");
$stmt->bind_param("i", $uczen_id);
$stmt->execute();
$przedmioty = $stmt->get_result();
$stmt->close();

// oceny ucznia pogrupowane po przedmiotach
$oceny = [];
$stmt = $db->prepare("SELECT przedmiot_id, wartosc, opis, data FROM oceny WHERE uczen_id = ? ORDER BY data");
$stmt->bind_param("i", $uczen_id);
$stmt->execute();
$wynik = $stmt->get_result();
while ($o = $wynik->fetch_assoc()) {
	$oceny[$o["przedmiot_id"]][] = $o;
}
$stmt->close();

$tytul = "Moje oceny";
include "includes/naglowek.php";
?>
		<h2>Moje oceny</h2>
		<p><?php echo htmlspecialchars($_SESSION["imie"] . " " . $_SESSION["nazwisko"]); ?></p>

		<table>
			<tr>
				<th>Przedmiot</th>
				<th>Nauczyciel</th>
				<th>Oceny</th>
			</tr>
			<?php while ($p = $przedmioty->fetch_assoc()) { ?>
			<tr>
				<td><?php echo htmlspecialchars($p["nazwa"]); ?></td>
				<td><?php echo htmlspecialchars($p["imie"] . " " . $p["nazwisko"]); ?></td>
				<td>
					<?php
					foreach ($oceny[$p["id"]] ?? [] as $o) {
						// opis i data pokazują się po najechaniu na ocenę
						$opis = $o["opis"] . " (" . date("d.m.Y", strtotime($o["data"])) . ")";
						echo "<span class=\"ocena\" title=\"" . htmlspecialchars($opis) . "\">" . htmlspecialchars($o["wartosc"]) . "</span> ";
					}
					?>
				</td>
			</tr>
			<?php } ?>
		</table>
<?php include "includes/stopka.php"; ?>
