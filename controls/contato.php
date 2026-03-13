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

try {
    $stm = $con->prepare('SELECT id FROM comentarios WHERE email = :email');
    $stm->bindParam(':email', $email);
    $stm->execute();

    $dados = $stm->fetch(PDO::FETCH_ASSOC);

    if (!$dados) {
        $stmtExec = $con->prepare('INSERT INTO comentarios(nome, email, telefone, mensagem) VALUES (:nome, :email, :telefone, :mensagem)');
        $stmtExec->bindParam(':nome', $nome);
        $stmtExec->bindParam(':email', $email);
        $stmtExec->bindParam(':telefone', $telefone);
        $stmtExec->bindParam(':mensagem', $comentario);
    } else {
        $stmtExec = $con->prepare('UPDATE comentarios SET mensagem = :msg WHERE id = :id');
        $stmtExec->bindParam(':msg', $comentario);
        $stmtExec->bindParam(':id', $dados['id']);
    }

    // Agora executamos o comando final
    if ($stmtExec->execute()) {
        $_SESSION['mensagem'] = "Mensagem enviada com sucesso!!!";
    } else {
        $_SESSION['mensagem'] = "Erro ao mandar comentário";
    }

    header('Location: ../contato.php');
    exit();
} catch (PDOException $e) {
    $_SESSION["mensagem"] = "Erro no banco de dados: " . $e->getMessage();
    header('Location: ../contato.php');
    exit;
}