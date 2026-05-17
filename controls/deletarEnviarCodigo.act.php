<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo('POST');
require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../util/enviar_email.php');

header('Content-Type: application/json');

$id = $_SESSION['id'] ?? null;

if (!$id) {
    echo json_encode(['ok' => false, 'erro' => 'Sessão expirada. Faça login novamente.']);
    exit;
}

$usuario = request("usuarios?id=eq.$id&select=nome,email");

if (empty($usuario) || isset($usuario['error'])) {
    echo json_encode(['ok' => false, 'erro' => 'Usuário não encontrado.']);
    exit;
}

$nome  = $usuario[0]['nome'];
$email = $usuario[0]['email'];

$codigo = random_int(100000, 999999);
$agora  = date('Y-m-d H:i:sO');

request("usuarios?id=eq.$id", 'PATCH', [
    'codigo_verificacao' => $codigo,
    'codigo_criado_em'   => $agora,
]);

$enviado = enviarEmail($email, $nome, $codigo, 'deletar_conta');

if (!$enviado) {
    echo json_encode(['ok' => false, 'erro' => 'Não foi possível enviar o e-mail. Tente novamente.']);
    exit;
}

$_SESSION['deletar_codigo_enviado'] = true;

echo json_encode(['ok' => true]);