<?php
session_start();
require('../includes/conexao.php');

$msg = "";

// Verifica se os campos existem e não estão vazios
if (
    empty($_POST['nome']) ||
    empty($_POST['email']) ||
    empty($_POST['senha']) ||
    empty($_POST['chave'])
) {
    $msg = "Preencha todos os campos.";
    $_SESSION["mensagem"] = $msg;
    header("Location: ../cadastro.php");
    exit;
}

$nome  = trim($_POST['nome']);
$email = trim($_POST['email']);
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
$chave = trim($_POST['chave']);

// Verifica se o email já existe
$stmt = $con->prepare("SELECT id FROM usuario WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $msg = "Email já cadastrado.";
    $_SESSION["mensagem"] = $msg;
    header("Location: ../cadastro.php");
    exit;
}

// Verifica se a chave (código do condomínio) existe
$stmt = $con->prepare("SELECT id FROM condominio WHERE codigo = ?");
$stmt->bind_param("s", $chave);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    $msg = "Chave de acesso incorreta.";
    $_SESSION["mensagem"] = $msg;
    header("Location: ../cadastro.php");
    exit;
}

$dados = $resultado->fetch_assoc();
$id_condominio = $dados['id'];

// Insere o usuário
$stmt = $con->prepare("INSERT INTO usuario (nome, email, senha, foto, id_condominio) VALUES (?, ?, ?, ?, ?)");
$foto = "url_img.png";

$stmt->bind_param("ssssi", $nome, $email, $senha, $foto, $id_condominio);

if ($stmt->execute()) {
    $msg = "Cadastro feito com sucesso!!!";
} else {
    $msg = "Erro ao realizar cadastro.";
}

$_SESSION["mensagem"] = $msg;
header("Location: ../cadastro.php");
exit;