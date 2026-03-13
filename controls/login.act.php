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

try {
    $sql = "SELECT * FROM usuario WHERE email = :email";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $dados = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dados) {
        $_SESSION["mensagem"] = "Email não cadastrado.";
        header("Location: ../cadastro.php");
        exit;
    }

    if (!password_verify($senha, $dados['senha'])) {
        $_SESSION["mensagem"] = "Senha incorreta.";
        header("Location: ../login.php");
        exit;
    }

    if ($dados['codigo'] != $chave) {
        $_SESSION["mensagem"] = "Chave de acesso incorreta.";
        header("Location: ../login.php");
        exit;
    }

    $_SESSION["id"] = $dados['id'];
    $_SESSION["login"] = true;

    header("Location: ../servicos.php");
    exit;
} catch (PDOException $e) {
    $_SESSION["mensagem"] = "Erro no banco de dados: " . $e->getMessage();
    header("Location: ../cadastro.php");
    exit;
}
