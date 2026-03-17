<?php
session_start();
require_once(__DIR__ . '/../../conexao.php');

$id_conversa = $_POST['id_conversa'];
$texto = $_POST['texto'];
$id_logado = $_SESSION['id'];

$dados = [
    "id_conversa" => $id_conversa,
    "id_remetente" => $id_logado,
    "texto" => $texto
];

$resultado = request("chat", "POST", $dados);

echo json_encode(["success" => !isset($resultado['error'])]);