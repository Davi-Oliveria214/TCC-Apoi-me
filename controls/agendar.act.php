<?php
session_start();
require_once(__DIR__ . '/../conexao.php');

// Verifica se os dados foram enviados
if (empty($_POST['data']) || empty($_POST['hora'])) {
    $_SESSION["mensagem"] = "Preencha todos os campos.";
    header("Location: ../agendar.php");
    exit;
}

// Pega os dados do formulário
$d = $_POST['data'];
$h = $_POST['hora'];
$obs = $_POST['observacao'] ?? null;

// Pega dados da sessão
$idServico   = $_SESSION['idServico'] ?? null;
$idPrestador = $_SESSION['idPrestador'] ?? null;
$idCliente   = $_SESSION['id'] ?? null;

// Validação extra
if (!$idServico || !$idPrestador || !$idCliente) {
    $_SESSION["mensagem"] = "Sessão inválida. Tente novamente.";
    header("Location: ../servicos.php");
    exit;
}

$servico = [
    "hora" => $h,
    "dia" => $d,
    "id_servico" => $idServico,
    "id_prestador" => $idPrestador,
    "id_cliente" => $idCliente,
    "observacao" => $obs
];

// Envia para API / banco
$sql = request("contratados", "POST", $servico);

// Verifica erro
if (empty($sql) || isset($sql['error'])) {
    $_SESSION["mensagem"] = "Erro ao agendar serviço.";
    header("Location: ../agendar.php");
    exit;
}

// Sucesso
$_SESSION["mensagem"] = "Serviço agendado com sucesso!";
header("Location: ../servicos.php");
exit;