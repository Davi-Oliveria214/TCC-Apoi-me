<?php
session_start();
require_once(__DIR__ . '/../conexao.php');

if (!isset($_SESSION['email_reset_aprovado'])) {
    header("Location: ../login.php");
    exit;
}

if (empty($_POST['senha']) || empty($_POST['rpt_senha'])) {
    $_SESSION["mensagem"] = "Preencha todos os campos.";
    header("Location: ../nova_senha.php");
    exit;
}

if ($_POST['senha'] != $_POST['rpt_senha']) {
    $_SESSION["mensagem"] = "A senhas não são iguais";
    header("Location: ../nova_senha.php");
    exit;
}

$email = $_SESSION['email_reset_aprovado'];
$novaSenha = password_hash($_POST['senha'], PASSWORD_BCRYPT);

$dados = [
    "senha" => $novaSenha,
    "codigo_verificacao" => null
];

$res = request("usuarios?email=eq.$email", "PATCH", $dados);

$msg = urlencode("Senha alterada! Por favor, faça login novamente.");
header("Location: ../util/limpar_sessao.php?msg=$msg");
exit();