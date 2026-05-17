<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo('POST');
require_once(__DIR__ . '/../conexao.php');

/* Validações básicas */
if (empty($_POST['confirmar']) || empty($_POST['codigo'])) {
    $_SESSION['mensagem'] = 'Dados inválidos. Tente novamente.';
    header('Location: ../usuario.php');
    exit;
}

if (empty($_SESSION['deletar_codigo_enviado'])) {
    $_SESSION['mensagem'] = 'Ação não autorizada.';
    header('Location: ../usuario.php');
    exit;
}

$id             = $_SESSION['id'] ?? null;
$codigoDigitado = trim($_POST['codigo']);

if (!$id) {
    $_SESSION['mensagem'] = 'Sessão expirada. Faça login novamente.';
    header('Location: ../index.php');
    exit;
}

/* Busca código salvo no banco */
$usuario = request("usuarios?id=eq.$id&select=email,codigo_verificacao,codigo_criado_em");

if (empty($usuario) || isset($usuario['error'])) {
    $_SESSION['mensagem'] = 'Usuário não encontrado.';
    header('Location: ../usuario.php');
    exit;
}

$u = $usuario[0];
$codigoSalvo = (string)($u['codigo_verificacao'] ?? '');
$criadoEm = $u['codigo_criado_em'] ?? null;

/* Verifica expiração (15 min) */
if ($criadoEm && (time() - strtotime($criadoEm)) / 60 > 15) {
    request("usuarios?id=eq.$id", 'PATCH', ['codigo_verificacao' => null]);
    $_SESSION['mensagem'] = 'O código expirou. Solicite um novo na página de perfil.';
    header('Location: ../usuario.php');
    exit;
}

/* Verifica código */
if ($codigoSalvo !== $codigoDigitado) {
    $_SESSION['mensagem'] = 'Código incorreto. Verifique seu e-mail e tente novamente.';
    header('Location: ../usuario.php');
    exit;
}

/* 1. Avaliações do usuário */
request("avaliacoes?id_usuario=eq.{$id}", 'DELETE');

/* 2. Contratos como morador */
request("contratados?id_morador=eq.{$id}", 'DELETE');

/* 3. Contratos dos seus serviços */
$servicos = request("servicos?id_prestador=eq.{$id}&select=id");

if (!empty($servicos) && !isset($servicos['error'])) {
    foreach ($servicos as $s) {
        request("contratados?id_servico=eq.{$s['id']}", 'DELETE');
    }
}

/* 4. Serviços anunciados */
request("servicos?id_prestador=eq.{$id}", 'DELETE');

/* 5. Avisos criados (síndico) */
request("avisos?id_usuario=eq.{$id}", 'DELETE');

/* 6. Registro do usuário */
request("usuarios?id=eq.{$id}", 'DELETE');

session_unset();

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

session_destroy();

session_start();
$_SESSION['mensagem'] = 'Sua conta foi excluída. Sentiremos sua falta.';
header('Location: ../index.php');
exit;