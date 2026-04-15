<?php
session_start();
require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../util/enviar_email.php');

$email = trim($_POST['email']);
$codigo = rand(100000, 999999);

$usuario = request("usuarios?email=eq." . urlencode($email) . "&select=id,nome,email_verificado", "GET");
$nome = $usuario[0]['nome'];

if (empty($usuario) || isset($usuario['error'])) {
    $_SESSION["mensagem"] = "Este e-mail não está cadastrado.";
    header("Location: ../enviar_codigo.php");
    exit;
}

$agora = date('Y-m-d H:i:sO');

$res_update = request("usuarios?email=eq." . urlencode($email), "PATCH", [
    "codigo_verificacao" => $codigo,
    "codigo_criado_em" => $agora
]);

if (isset($res_update['error'])) {
    $_SESSION["mensagem"] = "Erro ao processar solicitação no servidor.";
    header("Location: ../enviar_codigo.php");
    exit;
} else {
    $_SESSION['email_verificar'] = $email;
    if (!$usuario[0]['email_verificado']) {
        enviarEmail($email, $nome, $codigo, 'cadastro');
        header("Location: ../aviso_codigo.php");
        exit;
    }

    enviarEmail($email, $nome, $codigo, 'recuperar');
    header("Location: ../aviso_codigo.php");
    exit;
}

exit;