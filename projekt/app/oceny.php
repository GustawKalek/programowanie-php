<?php
require_once "includes/auth.php";
require_once "includes/db.php";
wymagaj_rol(["nauczyciel", "admin"]);

$moje_id = $_SESSION["user_id"];
$przedmiot_id = (int)($_GET["przedmiot"] ?? 0);

// admin widzi każdy przedmiot, nauczyciel tylko swoje
if ($_SESSION["rola"] == "admin") {
	$stmt = $db->prepare("SELECT nazwa FROM przedmioty WHERE id = ?");
	$stmt->bind_param("i", $przedmiot_id);
} else {
	$stmt = $db->prepare("SELECT nazwa FROM przedmioty WHERE id = ? AND nauczyciel_id = ?");
	$stmt->bind_param("ii", $przedmiot_id, $moje_id);
}
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

	// czy uczeń jest zapisany na ten przedmiot
	$stmt = $db->prepare("SELECT uczen_id FROM uczniowie_przedmioty WHERE uczen_id = ? AND przedmiot_id = ?");
	$stmt->bind_param("ii", $uczen_id, $przedmiot_id);
	$stmt->execute();
	$stmt->store_result();
	$zapisany = $stmt->num_rows > 0;
	$stmt->close();

	// sprawdzamy ucznia i format oceny (1-6, można dodać + albo -)
	if (!$zapisany) {
		$blad = "Ten uczeń nie jest zapisany na ten przedmiot.";
	} elseif (!preg_match("/^[1-6][+-]?$/", $wartosc)) {
		$blad = "Ocena musi być liczbą od 1 do 6, można dodać + albo -.";
	}

	if ($blad == "") {
		$stmt = $db->prepare("INSERT INTO oceny (uczen_id, przedmiot_id, nauczyciel_id, wartosc, opis) VALUES (?, ?, ?, ?, ?)");
		$stmt->bind_param("iiiss", $uczen_id, $przedmiot_id, $moje_id, $wartosc, $opis);
		$stmt->execute();
		$ocena_id = $stmt->insert_id;
		$stmt->close();

		// każda nowa ocena trafia też do historii
		$stmt = $db->prepare("INSERT INTO oceny_historia (ocena_id, uczen_id, przedmiot_id, akcja, nowa_wartosc, zmienil_id) VALUES (?, ?, ?, 'dodanie', ?, ?)");
		$stmt->bind_param("iiisi", $ocena_id, $uczen_id, $przedmiot_id, $wartosc, $moje_id);
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

// oceny według ucznia, żeby łatwo wypisać je w tabeli
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
		<p>
			<?php if ($_SESSION["rola"] == "admin") { ?>
			<a href="przedmioty.php">Wróć do przedmiotów</a> |
			<?php } else { ?>
			<a href="panel_nauczyciela.php">Wróć do moich przedmiotów</a> |
			<?php } ?>
			<a href="historia.php?przedmiot=<?php echo $przedmiot_id; ?>">Historia zmian tego przedmiotu</a>
		</p>

		<?php
		if ($blad != "") {
			echo "<p class=\"blad\">" . $blad . "</p>";
		}
		?>

		<table>
			<tr>
				<th>Uczeń</th>
				<th>Oceny</th>
				<th>Nowa ocena</th>
			</tr>
			<?php
			// wiersz z nazwą klasy, gdy zaczyna się następna klasa
			$poprzednia_klasa = "";
			while ($u = $uczniowie->fetch_assoc()) {
				if ($u["klasa"] != $poprzednia_klasa) {
					echo "<tr class=\"klasa\"><th colspan=\"3\">Klasa " . htmlspecialchars($u["klasa"]) . "</th></tr>";
					$poprzednia_klasa = $u["klasa"];
				}
			?>
			<tr>
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
