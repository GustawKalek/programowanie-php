<?php
require_once "includes/auth.php";

// czyścimy sesję i wracamy na stronę główną
$_SESSION = [];
session_destroy();

header("Location: index.php");
exit;
