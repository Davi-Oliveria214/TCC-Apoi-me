<?php
@session_start();
require_once './conexao.php';

if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['telefone']) || empty($_POST['comentario'])) {
    $_SESSION['mensagem'] = "Preencha todos os campos necessários";
    header('Location: ../contato.php');
    exit();
}

$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$comentario = $_POST['comentario'];

if (empty($_SESSION['login'])) {
    $stm = $con->prepare("SELECT id FROM usuario WHERE email = ?");
    $stm->bind_param("s", $email);
    $stm->execute();
    $res = $stm->get_result();

    if ($res->num_rows == 0) {
        $_SESSION['mensagem'] = "Email não cadastrado no sistema";
        header('Location: ../contato.php');
        exit();
    }

    $user = $res->fetch_assoc();
    $id_user = $user['id'];
} else {
    $id_user = $_SESSION['id'];
}

$stm = $con->prepare('SELECT id FROM comentarios WHERE email = ?');
$stm->bind_param('s', $email);
$stm->execute();
$res = $stm->get_result();

if ($res->num_rows == 0) {
    $stm = $con->prepare('INSERT INTO comentarios(nome, email, telefone, mensagem) VALUES (?, ?, ?, ?)');
    $stm->bind_param('ssss', $nome, $email, $telefone, $comentario);
} else {
    $stm = $con->prepare('UPDATE comentarios SET mensagem = ? WHERE email = ?');
    $stm->bind_param('ss', $comentario, $email);
}

if ($stm->execute()) {
    $_SESSION['mensagem'] = "Mensagem enviada com sucesso!!!";
} else {
    $_SESSION['mensagem'] = "Erro ao mandar comentário";
}

header('Location: ../contato.php');
exit();
