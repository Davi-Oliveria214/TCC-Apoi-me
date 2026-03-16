<?php
session_start();
require_once(__DIR__ . '/../conexao.php');

if (
    empty($_POST['email']) ||
    empty($_POST['senha']) ||
    empty($_POST['chave'])
) {
    $_SESSION["mensagem"] = "Preencha todos os campos.";
    header("Location: ../login.php");
    exit;
}

$email = trim($_POST['email']);
$senha = $_POST['senha'];
$chave = trim($_POST['chave']);

$sql = request("usuarios?email=eq.$email&select=*", "GET");

if (empty($sql) || isset($sql['error'])) {
    $_SESSION["mensagem"] = "Email não cadastrado.";
    header("Location: ../cadastro.php");
    exit;
}

$usuario = $sql[0];

if (!password_verify($senha, $usuario['senha'])) {
    $_SESSION["mensagem"] = "Senha incorreta.";
    header("Location: ../login.php");
    exit;
}

if ($usuario['codigo'] != $chave) {
    $_SESSION["mensagem"] = "Chave de acesso incorreta.";
    header("Location: ../login.php");
    exit;
}

$_SESSION["id"] = $usuario['id'];
$_SESSION["login"] = true;

header("Location: ../servicos.php");
exit;