<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

require_once(__DIR__ . '/../conexao.php');

$email = $_SESSION['email_verificar'] ?? $_POST['email'] ?? $_GET['email'] ?? null;
$codigoDigitado = $_POST['codigo'] ?? null;
$tipo_codigo = $_POST['tipo_codigo'] ?? $_SESSION['tipo_codigo'] ?? '';

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

        if (isset($tipo_codigo) && $tipo_codigo == 'recuperar') {
            header("Location: ../enviar_codigo.php");
        } else {
            header("Location: ../cadastro.php");
        }
        exit;
    }

    request("usuarios?email=eq." . urlencode($email), "PATCH", ["codigo_verificacao" => null]);

    if ($tipo_codigo == 'recuperar') {
        $_SESSION['email_reset_aprovado'] = $email;
        unset($_SESSION['email_verificar']);
        header("Location: ..redefinir_senha.php");
        exit;
    }

    request("usuarios?email=eq." . urlencode($email), "PATCH", ["email_verificado" => true]);

    unset($_SESSION['email_verificar']);
    $_SESSION["mensagem"] = "E-mail verificado com sucesso! Você já pode acessar sua conta.";
    header("Location: ../login.php");
    exit;
} else {
    $_SESSION["mensagem"] = "Código incorreto ou inválido. Verifique seu e-mail.";
    header("Location: ../codigo_verificar.php?email=" . urlencode($email));
    exit;
}