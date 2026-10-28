<?php
require_once "includes/auth.php";
require_once "includes/db.php";
wymagaj_roli("admin");

$id = (int)($_GET["id"] ?? 0);

$bledy = [];
$nazwa = "";

if ($id > 0 && $_SERVER["REQUEST_METHOD"] != "POST") {
	$stmt = $db->prepare("SELECT nazwa FROM przedmioty WHERE id = ?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$p = $stmt->get_result()->fetch_assoc();
	$stmt->close();

	if (!$p) {
		die("Nie ma takiego przedmiotu.");
	}
	$nazwa = $p["nazwa"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$nazwa = trim($_POST["nazwa"] ?? "");

	if ($nazwa == "") {
		$bledy[] = "Podaj nazwę przedmiotu.";
	}

	if (empty($bledy)) {
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
		if ($id == 0) {
			$stmt = $db->prepare("INSERT INTO przedmioty (nazwa) VALUES (?)");
			$stmt->bind_param("s", $nazwa);
		} else {
			$stmt = $db->prepare("UPDATE przedmioty SET nazwa = ? WHERE id = ?");
			$stmt->bind_param("si", $nazwa, $id);
		}
		$stmt->execute();
		$stmt->close();

		header("Location: przedmioty.php");
		exit;
	}
}

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
			<button type="submit">Zapisz</button>
			<a href="przedmioty.php">Anuluj</a>
		</form>
<?php include "includes/stopka.php"; ?>
