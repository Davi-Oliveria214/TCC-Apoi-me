<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

require_once(__DIR__ . '/../conexao.php');

if (empty($_POST['id_servico']) || empty($_POST['data']) || empty($_POST['hora'])) {
    $_SESSION["mensagem"] = "Preencha os campos";
    header("Location: ../servicos.php");
    exit();
}

$idServico = $_POST['id_servico'];
$d =  $_POST['data'];
$h = $_POST['hora'];
$obs = $_POST['observacao'];
$idCliente = $_SESSION['id'];

$dadosServico = request("servicos?select=id_prestador&id=eq.$idServico", "GET");
$idPrestador = $dadosServico[0]['id_prestador'];

if ($idPrestador == $idCliente) {
    $_SESSION["mensagem"] = "Você não pode contratar o serviço para se proprio!";
    header("Location: ../servicos.php");
    exit();
}

$existe = request("contratados?dia=eq.$d&hora=eq.$h&id_servico=eq.$idServico");

if (!empty($existe) && !isset($existe['error'])) {
    $_SESSION["mensagem"] = "Esse horário já foi reservado!";
    header("Location: ../servicos.php");
    exit();
}

$dadosParaSalvar = [
    "hora" => $h,
    "dia" => $d,
    "id_servico" => $idServico,
    "id_prestador" => $idPrestador,
    "id_cliente" => $idCliente,
    "observacao" => $obs
];

$sql = request("contratados", "POST", $dadosParaSalvar);

if (!$sql || isset($sql['error'])) {
    $_SESSION["mensagem"] = "Erro ao agendar: " . ($sql['error']['message'] ?? 'Erro desconhecido');
} else {
    $_SESSION["mensagem"] = "Serviço agendado com sucesso!";
}

header("Location: ../servicos.php");
exit();
