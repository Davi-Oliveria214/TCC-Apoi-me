<?php
require('../util/conexao.php');
header('Content-Type: application/json');

$codigo = $_GET['codigo'] ?? null;

if (!$codigo) {
    echo json_encode([]);
    exit;
}

$params = "codigo_condominio=eq.$codigo&order=criado_at.asc";
$res = request("mensagens?" . $params, "GET");

echo json_encode(is_array($res) ? $res : []);