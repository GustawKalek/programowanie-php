<?php
require_once "includes/auth.php";
require_once "includes/db.php";
wymagaj_roli("admin");

$id = (int)($_GET["id"] ?? 0);

$bledy = [];
$nazwa = "";
$nauczyciel_id = 0;

if ($id > 0 && $_SERVER["REQUEST_METHOD"] != "POST") {
	$stmt = $db->prepare("SELECT nazwa, nauczyciel_id FROM przedmioty WHERE id = ?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$p = $stmt->get_result()->fetch_assoc();
	$stmt->close();

	if (!$p) {
		die("Nie ma takiego przedmiotu.");
	}
	$nazwa = $p["nazwa"];
	$nauczyciel_id = (int)$p["nauczyciel_id"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$nazwa = trim($_POST["nazwa"] ?? "");
	$nauczyciel_id = (int)($_POST["nauczyciel_id"] ?? 0);

	if ($nazwa == "") {
		$bledy[] = "Podaj nazwę przedmiotu.";
	}

	if (empty($bledy)) {
		// nazwa przedmiotu nie może się powtarzać
		$stmt = $db->prepare("SELECT id FROM przedmioty WHERE nazwa = ? AND id != ?");
		$stmt->bind_param("si", $nazwa, $id);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$bledy[] = "Taki przedmiot już istnieje.";
		}
		$stmt->close();
	}

	if (empty($bledy)) {
		// 0 na liście to brak nauczyciela, w bazie zapisujemy wtedy NULL
		if ($nauczyciel_id > 0) {
			$nauczyciel = $nauczyciel_id;
		} else {
			$nauczyciel = null;
		}

		if ($id == 0) {
			$stmt = $db->prepare("INSERT INTO przedmioty (nazwa, nauczyciel_id) VALUES (?, ?)");
			$stmt->bind_param("si", $nazwa, $nauczyciel);
		} else {
			$stmt = $db->prepare("UPDATE przedmioty SET nazwa = ?, nauczyciel_id = ? WHERE id = ?");
			$stmt->bind_param("sii", $nazwa, $nauczyciel, $id);
		}
		$stmt->execute();
		$stmt->close();

		header("Location: przedmioty.php");
		exit;
	}
}

$nauczyciele = $db->query("SELECT id, imie, nazwisko FROM uzytkownicy WHERE rola = 'nauczyciel' ORDER BY nazwisko, imie");

if ($id > 0) {
	$tytul = "Edycja przedmiotu";
} else {
	$tytul = "Nowy przedmiot";
}
include "includes/naglowek.php";
?>
		<h2><?php echo $tytul; ?></h2>

		<?php
		foreach ($bledy as $blad) {
			echo "<p class=\"blad\">" . $blad . "</p>";
		}
		?>

		<form method="post">
			<p>
				<label for="nazwa">Nazwa:</label><br>
				<input type="text" name="nazwa" id="nazwa" value="<?php echo htmlspecialchars($nazwa); ?>" maxlength="50" required>
			</p>
			<p>
				<label for="nauczyciel_id">Nauczyciel:</label><br>
				<select name="nauczyciel_id" id="nauczyciel_id">
					<option value="0">(brak)</option>
					<?php
					while ($n = $nauczyciele->fetch_assoc()) {
						$zaznacz = "";
						if ($n["id"] == $nauczyciel_id) {
							$zaznacz = " selected";
						}
						echo "<option value=\"" . $n["id"] . "\"" . $zaznacz . ">" . htmlspecialchars($n["nazwisko"] . " " . $n["imie"]) . "</option>";
					}
					?>
				</select>
			</p>
			<button type="submit">Zapisz</button>
			<a href="przedmioty.php">Anuluj</a>
		</form>
<?php include "includes/stopka.php"; ?>
