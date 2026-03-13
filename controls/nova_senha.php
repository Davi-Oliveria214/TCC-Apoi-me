<?php
session_start();
require_once(__DIR__ . '/../conexao.php');

if (empty($_POST['email']) || empty($_POST['senha']) || empty($_POST['rptSenha'])) {
    $_SESSION["mensagem"] = "Preencha todos os campos";
    header("Location: ../esqueci_senha.php");
    exit;
}

if ($_POST["senha"] !== $_POST["rptSenha"]) {
    $_SESSION["mensagem"] = "Digite as mesmas senhas nos dois campos";
    header("Location: ../esqueci_senha.php");
    exit;
}

$email = trim($_POST['email']);
$novaSenhaHash = password_hash($_POST['senha'], PASSWORD_BCRYPT);

try {
    $stm = $con->prepare("SELECT id FROM usuario WHERE email = :email");
    $stm->bindParam(":email", $email);
    $stm->execute();

    $usuario = $stm->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        $_SESSION["mensagem"] = "Este e-mail não está cadastrado.";
        header("Location: ../esqueci_senha.php");
        exit;
    }

    $idUsuario = $usuario['id'];

    $stmUpdate = $con->prepare("UPDATE usuario SET senha = :senha WHERE id = :id");
    $stmUpdate->bindParam(":senha", $novaSenhaHash);
    $stmUpdate->bindParam(":id", $idUsuario);

    if ($stmUpdate->execute()) {
        $_SESSION["mensagem"] = "Senha alterada com sucesso!";
        header("Location: ../login.php");
    } else {
        $_SESSION["mensagem"] = "Erro ao alterar senha.";
        header("Location: ../esqueci_senha.php");
    }

    exit;
} catch (PDOException $e) {
    $_SESSION["mensagem"] = "Erro no banco de dados: " . $e->getMessage();
    header("Location: ../esqueci_senha.php");
    exit;
}