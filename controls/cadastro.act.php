<?php
session_start();
require('../includes/conexao.php');

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

// Verifica se as senhas são iguais
if ($_POST['senha'] !== $_POST['rptSenha']) {
    $_SESSION["mensagem"] = "As senhas não coincidem.";
    header("Location: ../cadastro.php");
    exit;
}

$nome  = trim($_POST['nome']);
$email = trim($_POST['email']);
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
$chave = trim($_POST['chave']);
$foto  = "url_img.png";

// Verifica se email já existe
$stmt = $con->prepare("SELECT id FROM usuario WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $_SESSION["mensagem"] = "Email já cadastrado.";
    header("Location: ../cadastro.php");
    exit;
}

// Verifica se o condomínio existe
$stmt = $con->prepare("SELECT codigo FROM condominio WHERE codigo = ?");
$stmt->bind_param("s", $chave);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    $_SESSION["mensagem"] = "Chave de acesso incorreta.";
    header("Location: ../cadastro.php");
    exit;
}

// Insere usuário
$stmt = $con->prepare("INSERT INTO usuario (nome, email, senha, foto, codigo) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $nome, $email, $senha, $foto, $chave);

if ($stmt->execute()) {
    $_SESSION["mensagem"] = "Cadastro realizado com sucesso!";
} else {
    $_SESSION["mensagem"] = "Erro ao realizar cadastro.";
}

header("Location: ../cadastro.php");
exit;
