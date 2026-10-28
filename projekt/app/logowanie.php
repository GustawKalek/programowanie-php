<?php
require_once "includes/auth.php";
require_once "includes/db.php";

$blad = "";
$login = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$login = trim($_POST["login"] ?? "");
	$haslo = $_POST["haslo"] ?? "";

	$stmt = $db->prepare("SELECT id, imie, nazwisko, haslo_hash, rola FROM uzytkownicy WHERE email = ?");
	$stmt->bind_param("s", $login);
	$stmt->execute();
	$uzytkownik = $stmt->get_result()->fetch_assoc();
	$stmt->close();

	if ($uzytkownik && password_verify($haslo, $uzytkownik["haslo_hash"])) {
		session_regenerate_id(true);

		$_SESSION["user_id"] = $uzytkownik["id"];
		$_SESSION["imie"] = $uzytkownik["imie"];
		$_SESSION["nazwisko"] = $uzytkownik["nazwisko"];
		$_SESSION["rola"] = $uzytkownik["rola"];

		if ($uzytkownik["rola"] == "admin") {
			header("Location: nauczyciele.php");
		} elseif ($uzytkownik["rola"] == "nauczyciel") {
			header("Location: panel_nauczyciela.php");
		} else {
			header("Location: index.php");
		}
		exit;
	} else {
		$blad = "Nieprawidłowy login lub hasło.";
	}
}

$tytul = "Logowanie";
include "includes/naglowek.php";
?>
		<h2>Logowanie</h2>

		<?php
		if ($blad != "") {
			echo "<p class=\"blad\">" . $blad . "</p>";
		}
		?>

		<form method="post">
			<p>
				<label for="login">Login:</label><br>
				<input type="text" name="login" id="login" value="<?php echo htmlspecialchars($login); ?>" required>
			</p>
			<p>
				<label for="haslo">Hasło:</label><br>
				<input type="password" name="haslo" id="haslo" required>
			</p>
			<button type="submit">Zaloguj</button>
		</form>
<?php include "includes/stopka.php"; ?>
