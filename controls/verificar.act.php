<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();
require_once(__DIR__ . '/../conexao.php');

$email          = $_SESSION['email_verificar'] ?? $_POST['email_recuperar'] ?? null;
$codigoDigitado = trim($_POST['codigo'] ?? '');
$tipo_codigo    = $_POST['tipo_codigo'] ?? $_SESSION['tipo_codigo'] ?? '';
$novo_email     = $_SESSION['novo_email'] ?? $_POST['novo_email'] ?? '';

/* ── Validação básica ─────────────────────────────────────── */
if (empty($email) || empty($codigoDigitado)) {
    $_SESSION['mensagem'] = 'Dados insuficientes para a verificação.';
    header('Location: ../login.php');
    exit;
}

$emailEnc = urlencode($email);

/* ── Busca o usuário pelo e-mail ─────────────────────────── */
$user = request(
    "usuarios?email=eq.{$emailEnc}&select=id,codigo_verificacao,codigo_criado_em",
    'GET'
);

if (empty($user) || isset($user['error'])) {
    $_SESSION['mensagem'] = 'Usuário não encontrado.';
    header('Location: ../verificar_acesso.php?etapa=enviar');
    exit;
}

$u             = $user[0];
$codigoSalvo   = (string)($u['codigo_verificacao'] ?? '');
$criadoEm      = $u['codigo_criado_em'] ?? null;
$id_usuario    = $u['id'];

/* ── Verifica código ─────────────────────────────────────── */
if ($codigoSalvo !== $codigoDigitado) {
    $_SESSION['mensagem'] = 'Código incorreto ou inválido. Verifique seu e-mail.';
    $redirect = '../verificar_acesso.php?etapa=codigo&email=' . $emailEnc;
    if ($tipo_codigo) $redirect .= '&tipo_codigo=' . urlencode($tipo_codigo);
    if ($novo_email)  $redirect .= '&novo_email='  . urlencode($novo_email);
    header("Location: $redirect");
    exit;
}

/* ── Verifica expiração (15 min) ─────────────────────────── */
if ($criadoEm && (time() - strtotime($criadoEm)) / 60 > 15) {
    request("usuarios?email=eq.{$emailEnc}", 'PATCH', ['codigo_verificacao' => null]);
    $_SESSION['mensagem'] = 'Este código expirou (limite de 15 min). Solicite um novo.';

    $redirect = $tipo_codigo === 'recuperar'
        ? '../verificar_acesso.php?etapa=enviar&tipo_envio=redefinir'
        : '../verificar_acesso.php?etapa=enviar';
    header("Location: $redirect");
    exit;
}

/* ── Código correto e dentro do prazo: limpa o código ───── */
request("usuarios?email=eq.{$emailEnc}", 'PATCH', ['codigo_verificacao' => null]);

/* ── Fluxo: recuperar senha ──────────────────────────────── */
if ($tipo_codigo === 'recuperar') {
    $_SESSION['email_reset_aprovado'] = $email;
    unset($_SESSION['email_verificar'], $_SESSION['tipo_codigo']);
    header('Location: ../verificar_acesso.php?etapa=senha');
    exit;
}

/* ── Fluxo: alterar e-mail ───────────────────────────────── */
if ($tipo_codigo === 'alterar_email') {
    if (empty($novo_email)) {
        $_SESSION['mensagem'] = 'Novo e-mail não informado. Tente novamente.';
        header('Location: ../usuario.php');
        exit;
    }

    $alterado = request("usuarios?id=eq.{$id_usuario}", 'PATCH', [
        'email' => $novo_email,
    ]);

    if (isset($alterado['error']) || empty($alterado)) {
        $_SESSION['mensagem'] = 'Erro ao alterar e-mail. Tente novamente.';
        header('Location: ../usuario.php');
        exit;
    }

    /* Desloga para forçar login com novo e-mail */
    session_unset();
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
    session_start();
    $_SESSION['mensagem'] = 'E-mail alterado com sucesso! Faça login com o novo endereço.';
    header('Location: ../login.php');
    exit;
}

request("usuarios?email=eq.{$emailEnc}", 'PATCH', ['email_verificado' => true]);

unset($_SESSION['email_verificar'], $_SESSION['tipo_codigo']);
$_SESSION['mensagem'] = 'E-mail verificado com sucesso! Você já pode acessar sua conta.';
header('Location: ../login.php');
exit;