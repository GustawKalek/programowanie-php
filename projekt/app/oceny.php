<?php
require_once "includes/auth.php";
require_once "includes/db.php";
wymagaj_roli("nauczyciel");

$nauczyciel_id = $_SESSION["user_id"];
$przedmiot_id = (int)($_GET["przedmiot"] ?? 0);

$stmt = $db->prepare("SELECT nazwa FROM przedmioty WHERE id = ? AND nauczyciel_id = ?");
$stmt->bind_param("ii", $przedmiot_id, $nauczyciel_id);
$stmt->execute();
$przedmiot = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$przedmiot) {
	die("Nie uczysz tego przedmiotu.");
}

$blad = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$uczen_id = (int)($_POST["uczen_id"] ?? 0);
	$wartosc = trim($_POST["wartosc"] ?? "");
	$opis = trim($_POST["opis"] ?? "");

	$stmt = $db->prepare("SELECT uczen_id FROM uczniowie_przedmioty WHERE uczen_id = ? AND przedmiot_id = ?");
	$stmt->bind_param("ii", $uczen_id, $przedmiot_id);
	$stmt->execute();
	$stmt->store_result();
	$zapisany = $stmt->num_rows > 0;
	$stmt->close();

	if (!$zapisany) {
		$blad = "Ten uczeń nie jest zapisany na ten przedmiot.";
	} elseif (!preg_match("/^[1-6][+-]?$/", $wartosc)) {
		$blad = "Ocena musi być liczbą od 1 do 6, można dodać + albo -.";
	}

	if ($blad == "") {
		$stmt = $db->prepare("INSERT INTO oceny (uczen_id, przedmiot_id, nauczyciel_id, wartosc, opis) VALUES (?, ?, ?, ?, ?)");
		$stmt->bind_param("iiiss", $uczen_id, $przedmiot_id, $nauczyciel_id, $wartosc, $opis);
		$stmt->execute();
		$ocena_id = $stmt->insert_id;
		$stmt->close();

		$stmt = $db->prepare("INSERT INTO oceny_historia (ocena_id, uczen_id, przedmiot_id, akcja, nowa_wartosc, zmienil_id) VALUES (?, ?, ?, 'dodanie', ?, ?)");
		$stmt->bind_param("iiisi", $ocena_id, $uczen_id, $przedmiot_id, $wartosc, $nauczyciel_id);
		$stmt->execute();
		$stmt->close();

		header("Location: oceny.php?przedmiot=" . $przedmiot_id);
		exit;
	}
}

$stmt = $db->prepare("SELECT u.id, u.imie, u.nazwisko, u.klasa FROM uzytkownicy u
	JOIN uczniowie_przedmioty up ON up.uczen_id = u.id
	WHERE up.przedmiot_id = ?
	ORDER BY u.klasa, u.nazwisko, u.imie");
$stmt->bind_param("i", $przedmiot_id);
$stmt->execute();
$uczniowie = $stmt->get_result();
$stmt->close();

$oceny = [];
$stmt = $db->prepare("SELECT id, uczen_id, wartosc, opis FROM oceny WHERE przedmiot_id = ? ORDER BY data");
$stmt->bind_param("i", $przedmiot_id);
$stmt->execute();
$wynik = $stmt->get_result();
while ($o = $wynik->fetch_assoc()) {
	$oceny[$o["uczen_id"]][] = $o;
}
$stmt->close();

$tytul = "Oceny - " . $przedmiot["nazwa"];
include "includes/naglowek.php";
?>
		<h2><?php echo htmlspecialchars($przedmiot["nazwa"]); ?></h2>
		<p><a href="panel_nauczyciela.php">Wróć do moich przedmiotów</a></p>

		<?php
		if ($blad != "") {
			echo "<p class=\"blad\">" . $blad . "</p>";
		}
		?>

		<table>
			<tr>
				<th>Klasa</th>
				<th>Uczeń</th>
				<th>Oceny</th>
				<th>Nowa ocena</th>
			</tr>
			<?php while ($u = $uczniowie->fetch_assoc()) { ?>
			<tr>
				<td><?php echo htmlspecialchars($u["klasa"]); ?></td>
				<td><?php echo htmlspecialchars($u["nazwisko"] . " " . $u["imie"]); ?></td>
				<td>
					<?php
					foreach ($oceny[$u["id"]] ?? [] as $o) {
						echo "<a class=\"ocena\" href=\"ocena_form.php?id=" . $o["id"] . "\" title=\"" . htmlspecialchars($o["opis"]) . "\">" . htmlspecialchars($o["wartosc"]) . "</a> ";
					}
					?>
				</td>
				<td>
					<form method="post">
						<input type="hidden" name="uczen_id" value="<?php echo $u["id"]; ?>">
						<input type="text" name="wartosc" size="2" maxlength="2" placeholder="5" required>
						<input type="text" name="opis" size="15" maxlength="100" placeholder="np. sprawdzian">
						<button type="submit">Dodaj</button>
					</form>
				</td>
			</tr>
			<?php } ?>
		</table>
<?php include "includes/stopka.php"; ?>
