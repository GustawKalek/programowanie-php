<?php
require_once "includes/auth.php";
require_once "includes/db.php";
wymagaj_roli("admin");

$id = (int)($_GET["id"] ?? 0);

$bledy = [];
$imie = "";
$nazwisko = "";
$email = "";

// edycja: wczytanie danych nauczyciela
if ($id > 0 && $_SERVER["REQUEST_METHOD"] != "POST") {
	$stmt = $db->prepare("SELECT imie, nazwisko, email FROM uzytkownicy WHERE id = ? AND rola = 'nauczyciel'");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$n = $stmt->get_result()->fetch_assoc();
	$stmt->close();

	if (!$n) {
		die("Nie ma takiego nauczyciela.");
	}
	$imie = $n["imie"];
	$nazwisko = $n["nazwisko"];
	$email = $n["email"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$imie = trim($_POST["imie"] ?? "");
	$nazwisko = trim($_POST["nazwisko"] ?? "");
	$email = trim($_POST["email"] ?? "");
	$haslo = $_POST["haslo"] ?? "";

	if ($imie == "" || $nazwisko == "" || $email == "") {
		$bledy[] = "Wypełnij imię, nazwisko i e-mail.";
	}
	if ($email != "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$bledy[] = "Niepoprawny adres e-mail.";
	}
	if ($id == 0 && $haslo == "") {
		$bledy[] = "Podaj hasło dla nowego nauczyciela.";
	}

	if (empty($bledy)) {
		// sprawdzamy, czy e-mail nie jest zajęty
		$stmt = $db->prepare("SELECT id FROM uzytkownicy WHERE email = ? AND id != ?");
		$stmt->bind_param("si", $email, $id);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$bledy[] = "Konto z tym adresem e-mail już istnieje.";
		}
		$stmt->close();
	}

	if (empty($bledy)) {
		// puste hasło przy edycji = hasło bez zmian
		if ($id == 0) {
			$hash = password_hash($haslo, PASSWORD_DEFAULT);
			$stmt = $db->prepare("INSERT INTO uzytkownicy (imie, nazwisko, email, haslo_hash, rola) VALUES (?, ?, ?, ?, 'nauczyciel')");
			$stmt->bind_param("ssss", $imie, $nazwisko, $email, $hash);
		} elseif ($haslo != "") {
			$hash = password_hash($haslo, PASSWORD_DEFAULT);
			$stmt = $db->prepare("UPDATE uzytkownicy SET imie = ?, nazwisko = ?, email = ?, haslo_hash = ? WHERE id = ? AND rola = 'nauczyciel'");
			$stmt->bind_param("ssssi", $imie, $nazwisko, $email, $hash, $id);
		} else {
			$stmt = $db->prepare("UPDATE uzytkownicy SET imie = ?, nazwisko = ?, email = ? WHERE id = ? AND rola = 'nauczyciel'");
			$stmt->bind_param("sssi", $imie, $nazwisko, $email, $id);
		}
		$stmt->execute();
		$stmt->close();

		header("Location: nauczyciele.php");
		exit;
	}
}

if ($id > 0) {
	$tytul = "Edycja nauczyciela";
} else {
	$tytul = "Nowy nauczyciel";
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
				<label for="imie">Imię:</label><br>
				<input type="text" name="imie" id="imie" value="<?php echo htmlspecialchars($imie); ?>" required>
			</p>
			<p>
				<label for="nazwisko">Nazwisko:</label><br>
				<input type="text" name="nazwisko" id="nazwisko" value="<?php echo htmlspecialchars($nazwisko); ?>" required>
			</p>
			<p>
				<label for="email">E-mail (login):</label><br>
				<input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
			</p>
			<p>
				<label for="haslo">Hasło:</label><br>
				<input type="password" name="haslo" id="haslo">
				<?php
				if ($id > 0) {
					echo "<br><small>Zostaw puste, jeśli hasło ma się nie zmienić.</small>";
				}
				?>
			</p>
			<button type="submit">Zapisz</button>
			<a href="nauczyciele.php">Anuluj</a>
		</form>
<?php include "includes/stopka.php"; ?>
