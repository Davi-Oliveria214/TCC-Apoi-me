<?php
session_start();
require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../util/enviar_email.php');

if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['comentario'])) {
    $_SESSION['mensagem'] = "Preencha todos os campos necessários";
    header('Location: ../contato.php');
    exit();
}

$nome = $_POST['nome'];
$email = trim($_POST['email']);
$comentario = $_POST['comentario'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['mensagem'] = "Esse email não existe!";
    header('Location: ../contato.php');
    exit();
}

emailContato($email, $nome, $comentario);

header('Location: ../contato.php');
exit();