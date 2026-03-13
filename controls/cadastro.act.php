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
$foto  = "../icon/user.png";

try {
    $stmt = $con->prepare("SELECT id FROM usuario WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    if ($stmt->fetch()) {
        $_SESSION["mensagem"] = "Email já cadastrado.";
        header("Location: ../cadastro.php");
        exit;
    }

    $stmt = $con->prepare("SELECT codigo FROM condominio WHERE codigo = :chave");
    $stmt->bindParam(":chave", $chave);
    $stmt->execute();

    if (!$stmt->fetch()) {
        $_SESSION["mensagem"] = "Chave de acesso incorreta.";
        header("Location: ../cadastro.php");
        exit;
    }

    $stmt = $con->prepare("INSERT INTO usuario (nome, email, senha, imagem, codigo) VALUES (:nome, :email, :senha, :imagem, :chave)");

    $stmt->bindParam(":nome", $nome);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":senha", $senha);
    $stmt->bindParam(":imagem", $foto);
    $stmt->bindParam(":chave", $chave);

    if ($stmt->execute()) {
        $_SESSION["mensagem"] = "Cadastro realizado com sucesso!";
        header("Location: ../login.php");
    } else {
        $_SESSION["mensagem"] = "Erro ao realizar cadastro.";
        header("Location: ../cadastro.php");
    }

    exit;
} catch (PDOException $e) {
    $_SESSION["mensagem"] = "Erro no banco de dados: " . $e->getMessage();
    header("Location: ../cadastro.php");
    exit;
}