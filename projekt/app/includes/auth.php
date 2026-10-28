<?php
session_start();

function czy_zalogowany() {
	return isset($_SESSION["user_id"]);
}

function wymagaj_roli($rola) {
	if (!czy_zalogowany() || $_SESSION["rola"] != $rola) {
		header("Location: logowanie.php");
		exit;
	}
}
