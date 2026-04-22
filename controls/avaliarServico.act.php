<?php
require_once(__DIR__ . '/../conexao.php');
session_start();

$nota = $_POST['nota'];

if ($nota == 0) {
    $_SESSION["mensagem"] = "Escolha ao menos uma estrela para nota!!";
    header("Location: ../historico.php");
    exit;
}

$id_servico = $_POST['id'];
$comentario = $_POST['comentario'];

$_SESSION["mensagem"] = "Estamos em produção dessa mecânica!!!";
header("Location: ../historico.php");
exit;