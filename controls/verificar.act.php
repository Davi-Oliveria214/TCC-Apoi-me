<?php
session_start();
require_once(__DIR__ . '/../conexao.php');

$email = $_SESSION['email_verificar'] ?? $_POST['email_recuperar'] ?? null;
$codigoDigitado = $_POST['codigo'];

if (!$email) {
    $_SESSION["mensagem"] = "Sessão expirada. Solicite um novo link.";
    header("Location: ../esqueci_senha.php");
    exit;
}

$user = request("usuarios?email=eq.$email&select=codigo_verificacao,codigo_criado_em", "GET");

if (!empty($user) && $user[0]['codigo_verificacao'] == $codigoDigitado) {

    $tempoCriacao = strtotime($user[0]['codigo_criado_em']);
    $tempoAgora = time();
    $diferencaMinutos = ($tempoAgora - $tempoCriacao) / 60;

    if ($diferencaMinutos > 15) {
        request("usuarios?email=eq.$email", "PATCH", ["codigo_verificacao" => null]);
        
        $_SESSION["mensagem"] = "Este código expirou, limite de 15 min. Solicite um novo.";
        header("Location: ../esqueci_senha.php");
        exit;
    }

    request("usuarios?email=eq.$email", "PATCH", ["codigo_verificacao" => null]);

    if (isset($_SESSION['fluxo']) && $_SESSION['fluxo'] === 'recuperacao') {
        $_SESSION['email_reset_aprovado'] = $email;
        unset($_SESSION['fluxo']);
        header("Location: ../nova_senha.php");
        exit;
    }

    request("usuarios?email=eq.$email", "PATCH", ["email_verificado" => true]);
    unset($_SESSION['email_verificar'], $_SESSION['fluxo']);
    $_SESSION["mensagem"] = "E-mail verificado! Faça login.";
    header("Location: ../login.php");
    exit;

} else {
    $_SESSION["mensagem"] = "Código incorreto ou inválido.";
    header("Location: ../codigo_verificar.php?email=$email");
    exit;
}