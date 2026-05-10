<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

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
$tipo = $_POST['tipo'];
$nota = $_POST['nota'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['mensagem'] = "Esse email não existe!";
    header('Location: ../contato.php');
    exit();
}

$enviado = emailContato($email, $nome, $comentario, $tipo, $nota);

if ($enviado) {
    $_SESSION["mensagem"] = "Sua mensagem foi enviada com sucesso!";
} else {
    $_SESSION["mensagem"] = "Erro ao enviar contato. Tente novamente mais tarde.";
}

header('Location: ../contato.php');
exit();
