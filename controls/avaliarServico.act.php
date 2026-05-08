<?php
require_once(__DIR__ . '/../conexao.php');
session_start();

$nota = $_POST['nota'];

if ($nota == 0) {
    $_SESSION["mensagem"] = "Escolha ao menos uma estrela para nota!!";
    header("Location: ../historico.php");
    exit;
}

if (empty($_POST['id_servico']) || empty($_SESSION['id'])) {
    $_SESSION["mensagem"] = "Erro ao enviar avaliação.";
    header("Location: ../historico.php");
    exit;
}

$id_cliente = $_SESSION['id'];
$id_servico = $_POST['id_servico'];
$comentario = $_POST['comentario'];
$id_contrato = $_POST['id_contrato'];

$dadosSalvar = [
    "nota" => $nota,
    "comentario" => $comentario,
    "id_servico" => $id_servico,
    "id_cliente" => $id_cliente,
    "id_contrato" => $id_contrato
];

$avaliar = request("avaliacao", "POST", $dadosSalvar);

if (!$avaliar || isset($avaliar['error'])) {
    $_SESSION["mensagem"] = "Erro ao enviar avaliação!";
    header("Location: ../historico.php");
    exit;
}

$status = request("contratados?id=eq.{$id_contrato}", "PATCH", [
    "avaliar" => true
]);

if (!$status || isset($status['error'])) {
    $_SESSION["mensagem"] = "Avaliação salva, mas erro ao atualizar status.";
} else {
    $_SESSION["mensagem"] = "Avaliação enviada com sucesso!";
}

header("Location: ../historico.php");
exit;