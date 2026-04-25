<?php
session_start();

$_SESSION["mensagem"] = "Você precisa estar logado para agendar!";
$_SESSION["tipo"] = "erro";

header("Location: ../login.php");
exit;