<?php
session_start();
require_once(__DIR__ . '/../conexao.php');

$idServico = $_POST['id_servico'];
$d = $_POST['data'];
$h = $_POST['hora'];
$obs = $_POST['observacao'];
$idCliente = $_SESSION['id'];

$dadosServico = request("servicos?select=id_prestador&id=eq.$idServico", "GET");
$idPrestador = $dadosServico[0]['id_prestador'];

$dadosParaSalvar = [
    "hora" => $h,
    "dia" => $d,
    "id_servico" => $idServico,
    "id_prestador" => $idPrestador,
    "id_cliente" => $idCliente,
    "observacao" => $obs
];

$sql = request("contratados", "POST", $dadosParaSalvar);

if (isset($sql['error'])) {
    http_response_code(400);
    $_SESSION["mensagem"] = "Erro ao agendar: " . $sql['error']['message'];
    echo json_encode(["status" => "erro", "mensagem" => $_SESSION["mensagem"]]);
} else {
    $_SESSION["mensagem"] = "Serviço agendado com sucesso!";
    echo json_encode(["status" => "sucesso"]);
}