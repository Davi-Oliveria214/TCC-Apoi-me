<?php
session_start();
require_once(__DIR__ . '/../conexao.php');

if (
    empty($_POST['nome']) ||
    empty($_POST['email']) ||
    empty($_POST['senha']) ||
    empty($_POST['rptSenha']) ||
    empty($_POST['chave'])
) {
    $_SESSION["mensagem"] = "Preencha todos os campos.";
    header("Location: ../cadastro.php");
    exit;
}

if ($_POST['senha'] !== $_POST['rptSenha']) {
    $_SESSION["mensagem"] = "As senhas não coincidem.";
    header("Location: ../cadastro.php");
    exit;
}

$nome  = trim($_POST['nome']);
$email = trim($_POST['email']);
$senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
$chave = trim($_POST['chave']);
$img  = "../icon/user.png";

$sql = request("usuario?email=eq.$email&select=id", "GET");

if (!empty($sql) && !isset($sql['error'])) {
    $_SESSION["mensagem"] = "Email já cadastrado.";
    header("Location: ../cadastro.php");
    exit;
}

$sqlChave = request("condominio?codigo=eq.$chave&select=id");

if (empty($sqlChave) && isset($sqlChave['error'])) {
    $_SESSION["mensagem"] = "Chave de acesso incorreta.";
    header("Location: ../cadastro.php");
    exit;
}

$dados = ["nome" => $nome, "email" => $email, "senha" => $senha, "imagem" => $img, "codigo" => $chave];

$res = request("usuario", "POST", $dados);

if (!isset($res['error'])) {
    $_SESSION["mensagem"] = "Cadastro realizado com sucesso!";
    header("Location: ../login.php");
} else {
    $_SESSION["mensagem"] = "Erro ao realizar cadastro.";
    header("Location: ../cadastro.php");
}

exit;