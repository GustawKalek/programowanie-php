<?php
require_once "includes/auth.php";
require_once "includes/db.php";
wymagaj_roli("admin");

$id = (int)($_GET["id"] ?? 0);

$bledy = [];
$imie = "";
$nazwisko = "";
$email = "";
$klasa = "";
$wybrane = [];

if ($id > 0 && $_SERVER["REQUEST_METHOD"] != "POST") {
	$stmt = $db->prepare("SELECT imie, nazwisko, email, klasa FROM uzytkownicy WHERE id = ? AND rola = 'uczen'");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$u = $stmt->get_result()->fetch_assoc();
	$stmt->close();

	if (!$u) {
		die("Nie ma takiego ucznia.");
	}
	$imie = $u["imie"];
	$nazwisko = $u["nazwisko"];
	$email = $u["email"];
	$klasa = $u["klasa"];

	$stmt = $db->prepare("SELECT przedmiot_id FROM uczniowie_przedmioty WHERE uczen_id = ?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$wynik = $stmt->get_result();
	while ($w = $wynik->fetch_assoc()) {
		$wybrane[] = $w["przedmiot_id"];
	}
	$stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$imie = trim($_POST["imie"] ?? "");
	$nazwisko = trim($_POST["nazwisko"] ?? "");
	$email = trim($_POST["email"] ?? "");
	$klasa = trim($_POST["klasa"] ?? "");
	$haslo = $_POST["haslo"] ?? "";

	foreach ($_POST["przedmioty"] ?? [] as $p) {
		$wybrane[] = (int)$p;
	}

	if ($imie == "" || $nazwisko == "" || $email == "" || $klasa == "") {
		$bledy[] = "Wypełnij imię, nazwisko, e-mail i klasę.";
	}
	if ($email != "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$bledy[] = "Niepoprawny adres e-mail.";
	}
	if ($id == 0 && $haslo == "") {
		$bledy[] = "Podaj hasło dla nowego ucznia.";
	}

	if (empty($bledy)) {
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
		if ($id == 0) {
			$hash = password_hash($haslo, PASSWORD_DEFAULT);
			$stmt = $db->prepare("INSERT INTO uzytkownicy (imie, nazwisko, email, haslo_hash, rola, klasa) VALUES (?, ?, ?, ?, 'uczen', ?)");
			$stmt->bind_param("sssss", $imie, $nazwisko, $email, $hash, $klasa);
		} elseif ($haslo != "") {
			$hash = password_hash($haslo, PASSWORD_DEFAULT);
			$stmt = $db->prepare("UPDATE uzytkownicy SET imie = ?, nazwisko = ?, email = ?, klasa = ?, haslo_hash = ? WHERE id = ? AND rola = 'uczen'");
			$stmt->bind_param("sssssi", $imie, $nazwisko, $email, $klasa, $hash, $id);
		} else {
			$stmt = $db->prepare("UPDATE uzytkownicy SET imie = ?, nazwisko = ?, email = ?, klasa = ? WHERE id = ? AND rola = 'uczen'");
			$stmt->bind_param("ssssi", $imie, $nazwisko, $email, $klasa, $id);
		}
		$stmt->execute();
		if ($id == 0) {
			$id = $stmt->insert_id;
		}
		$stmt->close();

		$stmt = $db->prepare("DELETE FROM uczniowie_przedmioty WHERE uczen_id = ?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->close();

		$stmt = $db->prepare("INSERT INTO uczniowie_przedmioty (uczen_id, przedmiot_id) VALUES (?, ?)");
		foreach ($wybrane as $przedmiot_id) {
			$stmt->bind_param("ii", $id, $przedmiot_id);
			$stmt->execute();
		}
		$stmt->close();

		header("Location: uczniowie.php");
		exit;
	}
}

$przedmioty = $db->query("SELECT id, nazwa FROM przedmioty ORDER BY nazwa");

if ($id > 0) {
	$tytul = "Edycja ucznia";
} else {
	$tytul = "Nowy uczeń";
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
				<label for="klasa">Klasa:</label><br>
				<input type="text" name="klasa" id="klasa" value="<?php echo htmlspecialchars($klasa); ?>" maxlength="10" placeholder="np. 2A" required>
			</p>
			<p>
				Przedmioty:<br>
				<?php
				while ($p = $przedmioty->fetch_assoc()) {
					$zaznacz = "";
					if (in_array($p["id"], $wybrane)) {
						$zaznacz = " checked";
					}
					echo "<label><input type=\"checkbox\" name=\"przedmioty[]\" value=\"" . $p["id"] . "\"" . $zaznacz . "> " . htmlspecialchars($p["nazwa"]) . "</label><br>";
				}
				?>
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
			<a href="uczniowie.php">Anuluj</a>
		</form>
<?php include "includes/stopka.php"; ?>
