<?php
session_start();
require_once(__DIR__ . '/../conexao.php');

$id = $_SESSION['id'];
$id_servico = $_POST['id_servico'];
$ativo = filter_var($_POST['novo_status'], FILTER_VALIDATE_BOOLEAN);

$verificar = request("servicos?id_prestador=eq.{$id}&id=eq.{$id_servico}", "GET");

if (empty($verificar) || isset($verificar['error'])) {
    $_SESSION["mensagem"] = "Erro: Serviço não encontrado ou você não tem permissão.";
    header("Location: ../oferecidos.php");
    exit;
}

$dados = [
    "status" => $ativo
];

$resp = request("servicos?id_prestador=eq.{$id}&id=eq.{$id_servico}", "PATCH", $dados);

if (isset($resp['error'])) {
    $_SESSION["mensagem"] = "Não foi possível alterar os status do serviço.";
} else {
    $_SESSION["mensagem"] = "Status de serviço alterado com sucesso!";
}

header("Location: ../oferecidos.php");
exit;
