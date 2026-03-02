<?php
session_start();
require('../includes/conexao.php');

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

// Busca usuário pelo email
$sql = mysqli_query($con, "SELECT * FROM usuario WHERE email = '$email'");
$usuario = mysqli_fetch_assoc($sql);

if (!$usuario) {
    $_SESSION["mensagem"] = "Email não cadastrado.";
    header("Location: ../cadastro.php");
    exit;
}

// Verifica senha (CORRIGIDO)
if (!password_verify($senha, $usuario['senha'])) {
    $_SESSION["mensagem"] = "Senha incorreta.";
    header("Location: ../login.php");
    exit;
}

// Verifica chave do condomínio
if ($usuario['codigo'] != $chave) {
    $_SESSION["mensagem"] = "Chave de acesso incorreta.";
    header("Location: ../login.php");
    exit;
}

// Login OK
$_SESSION["id"] = $usuario['id'];
$_SESSION["login"] = true;

header("Location: ../servicos.php");
exit;