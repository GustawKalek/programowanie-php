<?php
// sesja jest potrzebna na każdej stronie z logowaniem
session_start();

// czy ktoś jest zalogowany
function czy_zalogowany() {
	return isset($_SESSION["user_id"]);
}

// jak nie ma odpowiedniej roli, odsyła do logowania
function wymagaj_roli($rola) {
	if (!czy_zalogowany() || $_SESSION["rola"] != $rola) {
		header("Location: logowanie.php");
		exit;
	}
}

// to samo, ale można podać kilka ról
function wymagaj_rol($role) {
	if (!czy_zalogowany() || !in_array($_SESSION["rola"], $role)) {
		header("Location: logowanie.php");
		exit;
	}
}
