<?php
require_once "includes/auth.php";
require_once "includes/db.php";
wymagaj_roli("nauczyciel");

$nauczyciel_id = $_SESSION["user_id"];
$id = (int)($_GET["id"] ?? 0);

$stmt = $db->prepare("SELECT o.uczen_id, o.przedmiot_id, o.wartosc, o.opis, u.imie, u.nazwisko, p.nazwa
	FROM oceny o
	JOIN uzytkownicy u ON u.id = o.uczen_id
	JOIN przedmioty p ON p.id = o.przedmiot_id
	WHERE o.id = ? AND p.nauczyciel_id = ?");
$stmt->bind_param("ii", $id, $nauczyciel_id);
$stmt->execute();
$ocena = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$ocena) {
	die("Nie ma takiej oceny albo nie uczysz tego przedmiotu.");
}

$blad = "";
$wartosc = $ocena["wartosc"];
$opis = $ocena["opis"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST["usun"])) {
		$stmt = $db->prepare("DELETE FROM oceny WHERE id = ?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->close();

		$stmt = $db->prepare("INSERT INTO oceny_historia (ocena_id, uczen_id, przedmiot_id, akcja, stara_wartosc, zmienil_id) VALUES (?, ?, ?, 'usuniecie', ?, ?)");
		$stmt->bind_param("iiisi", $id, $ocena["uczen_id"], $ocena["przedmiot_id"], $ocena["wartosc"], $nauczyciel_id);
		$stmt->execute();
		$stmt->close();

		header("Location: oceny.php?przedmiot=" . $ocena["przedmiot_id"]);
		exit;
	}

	$wartosc = trim($_POST["wartosc"] ?? "");
	$opis = trim($_POST["opis"] ?? "");

	if (!preg_match("/^[1-6][+-]?$/", $wartosc)) {
		$blad = "Ocena musi być liczbą od 1 do 6, można dodać + albo -.";
	}

	if ($blad == "") {
		$stmt = $db->prepare("UPDATE oceny SET wartosc = ?, opis = ? WHERE id = ?");
		$stmt->bind_param("ssi", $wartosc, $opis, $id);
		$stmt->execute();
		$stmt->close();

		if ($wartosc != $ocena["wartosc"]) {
			$stmt = $db->prepare("INSERT INTO oceny_historia (ocena_id, uczen_id, przedmiot_id, akcja, stara_wartosc, nowa_wartosc, zmienil_id) VALUES (?, ?, ?, 'zmiana', ?, ?, ?)");
			$stmt->bind_param("iiissi", $id, $ocena["uczen_id"], $ocena["przedmiot_id"], $ocena["wartosc"], $wartosc, $nauczyciel_id);
			$stmt->execute();
			$stmt->close();
		}

		header("Location: oceny.php?przedmiot=" . $ocena["przedmiot_id"]);
		exit;
	}
}

$tytul = "Edycja oceny";
include "includes/naglowek.php";
?>
		<h2>Edycja oceny</h2>
		<p>
			Przedmiot: <?php echo htmlspecialchars($ocena["nazwa"]); ?><br>
			Uczeń: <?php echo htmlspecialchars($ocena["imie"] . " " . $ocena["nazwisko"]); ?>
		</p>

		<?php
		if ($blad != "") {
			echo "<p class=\"blad\">" . $blad . "</p>";
		}
		?>

		<form method="post">
			<p>
				<label for="wartosc">Ocena:</label><br>
				<input type="text" name="wartosc" id="wartosc" value="<?php echo htmlspecialchars($wartosc); ?>" maxlength="2" required>
			</p>
			<p>
				<label for="opis">Opis:</label><br>
				<input type="text" name="opis" id="opis" value="<?php echo htmlspecialchars($opis); ?>" maxlength="100">
			</p>
			<button type="submit">Zapisz</button>
			<button type="submit" name="usun" value="1" formnovalidate onclick="return confirm('Na pewno usunąć tę ocenę?');">Usuń ocenę</button>
			<a href="oceny.php?przedmiot=<?php echo $ocena["przedmiot_id"]; ?>">Anuluj</a>
		</form>
<?php include "includes/stopka.php"; ?>
