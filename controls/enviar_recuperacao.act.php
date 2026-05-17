<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../util/enviar_email.php');

$email  = trim($_POST['email']);
$codigo = rand(100000, 999999);

$usuario = request("usuarios?email=eq." . urlencode($email) . "&select=id,nome,email_verificado", "GET");

if (empty($usuario) || isset($usuario['error'])) {
    $_SESSION['mensagem'] = "Este e-mail não está cadastrado.";
    $_SESSION['tipo']     = "erro";
    header("Location: ../verificar_acesso.php?etapa=enviar");
    exit;
}

$nome = $usuario[0]['nome'];
$agora = date('Y-m-d H:i:sO');

$res_update = request("usuarios?email=eq." . urlencode($email), "PATCH", [
    "codigo_verificacao" => $codigo,
    "codigo_criado_em"   => $agora,
]);

if (isset($res_update['error'])) {
    $_SESSION['mensagem'] = "Erro ao processar solicitação no servidor.";
    $_SESSION['tipo']     = "erro";
    header("Location: ../verificar_acesso.php?etapa=enviar");
    exit;
}

$_SESSION['email_verificar'] = $email;

$categoria = $_POST['categoria'] ?? '';
$tipo_codigo = ($categoria === 'redefinir') ? 'recuperar' : 'cadastro';
$_SESSION['tipo_codigo'] = $tipo_codigo;

$fluxo = $usuario[0]['email_verificado'] ? 'recuperar' : 'cadastro';
enviarEmail($email, $nome, $codigo, $fluxo);

header("Location: ../verificar_acesso.php?etapa=aviso");
exit;