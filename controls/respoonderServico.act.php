<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();
exigirLogin();

require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../util/enviar_email.php');

$idContrato = $_POST['id_contrato'] ?? '';
$acao       = $_POST['acao'] ?? '';

if (empty($idContrato) || !in_array($acao, ['aceitar', 'cancelar'])) {
    $_SESSION["mensagem"] = "Ação inválida.";
    header("Location: ../anunciar.php");
    exit();
}

$idPrestador = $_SESSION['id'];

$contrato = request("contratados?id=eq.{$idContrato}&id_prestador=eq.{$idPrestador}&select=*");

if (empty($contrato) || isset($contrato['error'])) {
    $_SESSION["mensagem"] = "Contrato não encontrado.";
    header("Location: ../anunciar.php");
    exit();
}

$c = $contrato[0];

$cliente = request("usuarios?id=eq.{$c['id_cliente']}&select=nome,email");

if (empty($cliente) || isset($cliente['error'])) {
    $_SESSION["mensagem"] = "Cliente não encontrado.";
    header("Location: ../anunciar.php");
    exit();
}

$novoStatus = ($acao === 'aceitar') ? 'confirmado' : 'cancelado';

$atualizar = request(
    "contratados?id=eq.{$idContrato}",
    "PATCH",
    ["status" => $novoStatus]
);

if (isset($atualizar['error'])) {
    $_SESSION["mensagem"] = "Erro ao atualizar o contrato.";
    header("Location: ../anunciar.php");
    exit();
}

$fluxoEmail = ($acao === 'aceitar') ? 'confirmacao_cliente' : 'cancelamento_cliente';

enviarEmailServico(
    $cliente[0]['email'],
    $cliente[0]['nome'],
    $c['nome_servico'],
    $c['nome_prestador'],
    $c['dia'],
    $c['hora'],
    $fluxoEmail
);

$_SESSION["mensagem"] = ($acao === 'aceitar')
    ? "Serviço confirmado! O cliente foi notificado por e-mail."
    : "Serviço cancelado. O cliente foi notificado por e-mail.";

header("Location: ../anunciar.php");
exit();