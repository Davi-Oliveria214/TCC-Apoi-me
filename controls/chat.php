<?php
require('../util/conexao.php');

$codigo = $_GET['codigo'] ?? null;

if (!$codigo) {
    echo json_encode([]);
    exit;
}

$params = "codigo_condominio=eq.$codigo&order=criado_at.asc&limit=50";
$res = request("mensagens?" . $params, "GET");

if (isset($res['error'])) {
    echo json_encode([]);
} else {
    echo json_encode($res);
}