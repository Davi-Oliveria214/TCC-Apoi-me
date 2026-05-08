<?php
session_start();
require('../util/conexao.php');

header('Content-Type: application/json');

$usuario_id = $_POST['usuario_id'] ?? null;
$texto      = $_POST['mensagem'] ?? null;
$codigo     = $_POST['codigo'] ?? null;
$nome       = $_SESSION['nome'] ?? 'Morador'; 

if (!$texto || !$codigo) {
    echo json_encode(['error' => 'Mensagem ou código ausente']);
    exit;
}

$dados = [
    "usuario_id"        => (int)$usuario_id,
    "nome_usuario"      => $nome,
    "conteudo"          => $texto,
    "codigo_condominio" => (int)$codigo
];

$res = request("mensagens", "POST", $dados);

echo json_encode($res);