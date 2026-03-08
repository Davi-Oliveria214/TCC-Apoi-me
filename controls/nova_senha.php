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
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
$rptSenha = trim($_POST['rptSenha']);

$stm = $con->prepare("SELECT id FROM usuario WHERE email = ?");
$stm->bind_param("s", $email);
$stm->execute();
$resultado = $stm->get_result();

if ($resultado->num_rows == 0) {
    $_SESSION["mensagem"] = "Email não cadastrado";
    header("Location: ../esqueci_senha.php");
    exit;
}

$usuario = $resultado->fetch_assoc();
$idUsuario = $usuario['id'];

$stm = $con->prepare("UPDATE usuario SET senha = ? WHERE id = ?");
$stm->bind_param("si", $senha, $idUsuario);

if ($stm->execute()) {
    $_SESSION["mensagem"] = "Senha alterada com sucesso";
} else {
    $_SESSION["mensagem"] = "Erro ao alterar senha";
}

header("Location: ../esqueci_senha.php");
exit;