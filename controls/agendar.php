<?php
session_start();
require_once(__DIR__ . '/../conexao.php');

if (empty('data') || empty('hora')) {
    $_SESSION["mensagem"] = "Preencha todos os campos.";
    header("Location: ../agendar.php");
    exit;
}

$idCliente = $_SESSION['id'];