<?php
session_start();
require_once(__DIR__ . '/../conexao.php');

if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['telefone']) || empty($_POST['comentario'])) {
    $_SESSION['mensagem'] = "Preencha todos os campos necessários";
    header('Location: ../contato.php');
    exit();
}

$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$comentario = $_POST['comentario'];

$sql = request("feedback?email=eq.$email&select=id", "GET");

if (empty($sql) && !isset($sql['error'])) {
    $dados = ["nome" => $nome, "email" => $email, "telefone" => $telefone, "mensagem" => $comentario];

    $enviar = request("feedback", "POST", $dados);
} else {
    $id = $sql[0]['id'];
    $dados = ["mensagem" => $comentario];

    $enviar = request("feedback?id=eq.$id", "PATCH", $dados);
}

if (!isset($enviar['error'])) {
    $_SESSION['mensagem'] = "Mensagem enviada com sucesso!!!";
} else {
    $_SESSION['mensagem'] = "Erro ao mandar comentário";
}

header('Location: ../contato.php');
exit();