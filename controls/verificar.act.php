<?php
session_start();
require_once(__DIR__ . '/../conexao.php');

if (!isset($_SESSION['email_verificar'])) {
    header("Location: ../login.php");
    exit;
}

$email = $_SESSION['email_verificar'];
$codigoDigitado = $_POST['codigo'];

$user = request("usuarios?email=eq.$email&select=codigo_verificacao", "GET");

if (empty($user) || isset($user['error'])) {
    $_SESSION["mensagem"] = "Erro na verificação.";
    header("Location: ../cadastro.php");
    exit;
}

if ($user[0]['codigo_verificacao'] == $codigoDigitado) {
    if (isset($_SESSION['fluxo']) && $_SESSION['fluxo'] === 'recuperacao') {
        header("Location: ../nova_senha.php");
        exit;
    }

    $update = ["email_verificado" => true, "codigo_verificacao" => null];
    request("usuarios?email=eq.$email", "PATCH", $update);

    $_SESSION["mensagem"] = "E-mail verificado! Faça login.";
    unset($_SESSION['email_verificar']);
    header("Location: ../login.php");
    exit;
} else {
    $_SESSION["mensagem"] = "Código incorreto.";
    header("Location: ../codigo_verificar.php");
    exit;
}