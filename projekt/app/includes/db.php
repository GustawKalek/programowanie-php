<?php
require_once __DIR__ . "/config.php";

// błędy bazy jako wyjątki, żeby złapać je w try
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
	$db = new mysqli($db_host, $db_user, $db_pass, $db_name);
	$db->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
	// szczegóły tylko do logu, użytkownik widzi ogólny komunikat
	error_log("Błąd połączenia z bazą: " . $e->getMessage());
	die("Błąd połączenia z bazą danych.");
}
