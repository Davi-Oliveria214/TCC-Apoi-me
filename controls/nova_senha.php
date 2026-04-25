<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

require_once(__DIR__ . '/../conexao.php');

if (empty($_POST['email']) || empty($_POST['senha']) || empty($_POST['rptSenha'])) {
    $_SESSION["mensagem"] = "Preencha todos os campos";
    header("Location: ../esqueci_senha.php");
    exit;
}

if ($_POST["senha"] !== $_POST["rptSenha"]) {
    $_SESSION["mensagem"] = "Digite as mesmas senhas nos dois campos";
    header("Location: ../esqueci_senha.php");
    exit;
}

$email = trim($_POST['email']);
$novaSenhaHash = password_hash($_POST['senha'], PASSWORD_BCRYPT);

$usuario = request("usuarios?email=eq.$email&select=id", "GET");

if (empty($usuario) || isset($usuario['error'])) {
    $_SESSION["mensagem"] = "Este e-mail não está cadastrado.";
    header("Location: ../esqueci_senha.php");
    exit;
}

$idUsuario = $usuario[0]['id'];

$dados = ["senha" => $novaSenhaHash];
$res = request("usuarios?id=eq.$idUsuario", "PATCH", $dados);

if (!isset($res['error'])) {
    $_SESSION = array();

    session_destroy();

    session_start();
    $_SESSION["mensagem"] = "Senha alterada com sucesso! Faça login com a nova senha.";

    header("Location: ../login.php");
} else {
    $_SESSION["mensagem"] = "Erro ao alterar senha.";
    header("Location: ../esqueci_senha.php");
}

exit;