<?php
session_start();
require_once(__DIR__ . '/../conexao.php');

$email = $_SESSION['email_verificar'] ?? $_POST['email'] ?? $_GET['email'] ?? null;
$codigoDigitado = $_POST['codigo'] ?? null;

if (empty($email) || empty($codigoDigitado)) {
    $_SESSION["mensagem"] = "Dados insuficientes para a verificação.";
    header("Location: ../login.php");
    exit;
}

$user = request("usuarios?email=eq." . urlencode($email) . "&select=codigo_verificacao,codigo_criado_em", "GET");

if (!empty($user) && isset($user[0]['codigo_verificacao']) && $user[0]['codigo_verificacao'] == $codigoDigitado) {
    $tempoCriacao = strtotime($user[0]['codigo_criado_em']);
    $tempoAgora = time();
    $diferencaMinutos = ($tempoAgora - $tempoCriacao) / 60;

    if ($diferencaMinutos > 15) {
        request("usuarios?email=eq." . urlencode($email), "PATCH", ["codigo_verificacao" => null]);

        $_SESSION["mensagem"] = "Este código expirou (limite de 15 min). Solicite um novo.";

        if (isset($_SESSION['fluxo']) && $_SESSION['fluxo'] === 'recuperacao') {
            header("Location: ../esqueci_senha.php");
        } else {
            header("Location: ../cadastro.php");
        }
        exit;
    }

    request("usuarios?email=eq." . urlencode($email), "PATCH", ["codigo_verificacao" => null]);

    if (isset($_SESSION['fluxo']) && $_SESSION['fluxo'] === 'recuperacao') {
        $_SESSION['email_reset_aprovado'] = $email;
        unset($_SESSION['fluxo'], $_SESSION['email_verificar']);
        header("Location: ../nova_senha.php");
        exit;
    }

    request("usuarios?email=eq." . urlencode($email), "PATCH", ["email_verificado" => true]);

    unset($_SESSION['email_verificar'], $_SESSION['fluxo']);
    $_SESSION["mensagem"] = "E-mail verificado com sucesso! Você já pode acessar sua conta.";
    header("Location: ../login.php");
    exit;
} else {
    $_SESSION["mensagem"] = "Código incorreto ou inválido. Verifique seu e-mail.";
    header("Location: ../codigo_verificar.php?email=" . urlencode($email));
    exit;
}
