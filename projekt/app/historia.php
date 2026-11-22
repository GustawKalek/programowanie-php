<?php
require_once "includes/auth.php";
require_once "includes/db.php";

wymagaj_rol(["uczen", "nauczyciel", "admin"]);

$id = $_SESSION["user_id"];
$rola = $_SESSION["rola"];
$pokaz_ucznia = $rola != "uczen";
$przedmiot_id = (int)($_GET["przedmiot"] ?? 0);

// wspólna część zapytania, WHERE zależy od roli
$sql = "SELECT h.data, h.akcja, h.stara_wartosc, h.nowa_wartosc, p.nazwa, u.imie, u.nazwisko, z.imie AS z_imie, z.nazwisko AS z_nazwisko
	FROM oceny_historia h
	JOIN przedmioty p ON p.id = h.przedmiot_id
	JOIN uzytkownicy u ON u.id = h.uczen_id
	LEFT JOIN uzytkownicy z ON z.id = h.zmienil_id";

if ($rola == "uczen") {
	$stmt = $db->prepare($sql . " WHERE h.uczen_id = ? ORDER BY h.data DESC, h.id DESC");
	$stmt->bind_param("i", $id);
} elseif ($rola == "nauczyciel" && $przedmiot_id > 0) {
	$stmt = $db->prepare($sql . " WHERE p.nauczyciel_id = ? AND p.id = ? ORDER BY h.data DESC, h.id DESC");
	$stmt->bind_param("ii", $id, $przedmiot_id);
} elseif ($rola == "nauczyciel") {
	$stmt = $db->prepare($sql . " WHERE p.nauczyciel_id = ? ORDER BY h.data DESC, h.id DESC");
	$stmt->bind_param("i", $id);
} elseif ($przedmiot_id > 0) {
	$stmt = $db->prepare($sql . " WHERE p.id = ? ORDER BY h.data DESC, h.id DESC");
	$stmt->bind_param("i", $przedmiot_id);
} else {
	$stmt = $db->prepare($sql . " ORDER BY h.data DESC, h.id DESC");
}
$stmt->execute();
$historia = $stmt->get_result();
$stmt->close();

// nazwy akcji do wyświetlenia
$akcje = ["dodanie" => "dodanie", "zmiana" => "zmiana", "usuniecie" => "usunięcie"];

$tytul = "Historia ocen";
include "includes/naglowek.php";
?>
		<h2>Historia ocen</h2>

		<?php if ($historia->num_rows == 0) { ?>
		<p>Brak zmian w ocenach.</p>
		<?php } else { ?>
		<table>
			<tr>
				<th>Data</th>
				<th>Przedmiot</th>
				<?php if ($pokaz_ucznia) { ?>
				<th>Uczeń</th>
				<?php } ?>
				<th>Akcja</th>
				<th>Było</th>
				<th>Jest</th>
				<th>Kto zmienił</th>
			</tr>
			<?php while ($h = $historia->fetch_assoc()) { ?>
			<tr>
				<td><?php echo date("d.m.Y H:i", strtotime($h["data"])); ?></td>
				<td><?php echo htmlspecialchars($h["nazwa"]); ?></td>
				<?php if ($pokaz_ucznia) { ?>
				<td><?php echo htmlspecialchars($h["nazwisko"] . " " . $h["imie"]); ?></td>
				<?php } ?>
				<td><?php echo $akcje[$h["akcja"]]; ?></td>
				<td><?php echo htmlspecialchars($h["stara_wartosc"] ?? "-"); ?></td>
				<td><?php echo htmlspecialchars($h["nowa_wartosc"] ?? "-"); ?></td>
				<td><?php echo htmlspecialchars($h["z_imie"] . " " . $h["z_nazwisko"]); ?></td>
			</tr>
			<?php } ?>
		</table>
		<?php } ?>
<?php include "includes/stopka.php"; ?>
