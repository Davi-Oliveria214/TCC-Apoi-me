<?php
session_start();
require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../util/enviar_email.php');

$email = trim($_POST['email']);
$codigo = rand(100000, 999999);

$usuario = request("usuarios?email=eq." . urlencode($email) . "&select=id,nome", "GET");
$nome = $usuario['nome'];

if (empty($usuario) || isset($usuario['error'])) {
    $_SESSION["mensagem"] = "Este e-mail não está cadastrado.";
    header("Location: ../esqueci_senha.php");
    exit;
}

$agora = date('Y-m-d H:i:sO');

$res_update = request("usuarios?email=eq." . urlencode($email), "PATCH", [
    "codigo_verificacao" => $codigo,
    "codigo_criado_em" => $agora
]);

if (isset($res_update['error'])) {
    $_SESSION["mensagem"] = "Erro ao processar solicitação no servidor.";
    header("Location: ../esqueci_senha.php");
    exit;
} else {
    $_SESSION['email_verificar'] = $email;
    enviarEmail($email, $nome, $codigo, 'recuperar');
}

exit;