<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

require_once(__DIR__ . '/../conexao.php');

$email = $_SESSION['email_verificar'] ?? $_POST['email'] ?? $_GET['email'] ?? null;
$codigoDigitado = $_POST['codigo'] ?? null;
$tipo_codigo = $_POST['tipo_codigo'] ?? $_SESSION['tipo_codigo'] ?? '';
$novo_email = $_SESSION['novo_email'] ?? '';

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
            header("Location: ../verificar_acesso.php?etapa=enviar&tipo_envio=redefinir");
        } else {
            header("Location: ../verificar_acesso.php?etapa=enviar");
        }
        exit;
    }

    request("usuarios?email=eq." . urlencode($email), "PATCH", ["codigo_verificacao" => null]);

    if ($tipo_codigo == 'recuperar') {
        $_SESSION['email_reset_aprovado'] = $email;
        unset($_SESSION['email_verificar']);
        header("Location: ../verificar_acesso.php?etapa=senha");
        exit;
    }

    if ($tipo_codigo == 'alterar_email') {
        if (empty($novo_email)) {
            $_SESSION["mensagem"] = "Erro ao validar email!";
            header("Location: ../usuario.php");
            exit;
        }

        $dados = [
            "email" => $novo_email
        ];

        $alterado = request("usuarios?email=eq.{$email}", "PATCH", $dados);

        if (isset($alterado['error']) || $alterado === false) {
            $_SESSION["mensagem"] = "Erro ao alterar email!";
        } else {
            $_SESSION["mensagem"] = "Novo email alterado com sucesso!";
        }

        session_unset();
        session_destroy();
        session_start();
        header("Location: ../login.php");
        exit;
    }

    request("usuarios?email=eq." . urlencode($email), "PATCH", ["email_verificado" => true]);

    unset($_SESSION['email_verificar']);
    $_SESSION["mensagem"] = "E-mail verificado com sucesso! Você já pode acessar sua conta.";
    header("Location: ../login.php");
    exit;
} else {
    $_SESSION["mensagem"] = "Código incorreto ou inválido. Verifique seu e-mail.";
    header("Location: ../verificar_acesso.php?etapa=codigo&email=" . urlencode($email) . (!empty($tipo_codigo) ? "&tipo_codigo=" . urlencode($tipo_codigo) : '') . (!empty($novo_email) ? "&novo_email=" . urlencode($novo_email) : ''));
    exit;
}