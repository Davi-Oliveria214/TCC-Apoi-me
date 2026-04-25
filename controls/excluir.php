<?php
session_start();
require_once(__DIR__ . '/../conexao.php');

$id = $_SESSION['id'];
$id_servico = $_POST['id_servico'];

$verificar = request("servicos?id_prestador=eq.{$id}&id=eq.{$id_servico}", "GET");

if (empty($verificar) || isset($verificar['error'])) {
    $_SESSION["mensagem"] = "Erro: Serviço não encontrado ou você não tem permissão.";
    header("Location: ../anunciar.php");
    exit;
}

$del = request("servicos?id_prestador=eq.{$id}&id=eq.{$id_servico}", "DELETE");

if (isset($del['error'])) {
    $_SESSION["mensagem"] = "Não foi possível excluir o serviço.";
} else {
    $_SESSION["mensagem"] = "Serviço excluído com sucesso!";
}

header("Location: ../anunciar.php");
exit;