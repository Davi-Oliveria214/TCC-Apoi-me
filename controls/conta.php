<?php

require_once(__DIR__ . '/../includes/funcoes.php');
require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../util/enviar_email.php');

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

if ($acao === 'enviar_codigo') {

    exigirMetodo('POST');
    header('Content-Type: application/json');

    $id = $_SESSION['id'] ?? null;

    if (!$id) {
        echo json_encode(['ok' => false, 'erro' => 'Sessão expirada. Faça login novamente.']);
        exit;
    }

    $servicos = request("servicos?id_prestador=eq.{$id}&select=id", "GET");
    if (!empty($servicos) && !isset($servicos['error'])) {
        echo json_encode(['ok' => false, 'erro' => 'Você possui serviços anunciados. Remova-os antes de excluir sua conta.']);
        exit;
    }

    $agendamentos = request("contratados?or=(id_cliente.eq.{$id},id_prestador.eq.{$id})&confirmado=in.(pendente,confirmado)&select=id", "GET");
    if (!empty($agendamentos) && !isset($agendamentos['error'])) {
        echo json_encode(['ok' => false, 'erro' => 'Você possui agendamentos ativos ou pendentes. Cancele-os antes de excluir sua conta.']);
        exit;
    }

    $usuario = request("usuarios?id=eq.$id&select=nome,email");

    if (empty($usuario) || isset($usuario['error'])) {
        echo json_encode(['ok' => false, 'erro' => 'Usuário não encontrado.']);
        exit;
    }

    $nome  = $usuario[0]['nome'];
    $email = $usuario[0]['email'];

    $codigo = random_int(100000, 999999);
    $agora  = date('Y-m-d H:i:sO');

    request("usuarios?id=eq.$id", 'PATCH', [
        'codigo_verificacao' => $codigo,
        'codigo_criado_em'   => $agora,
    ]);

    $enviado = enviarEmail($email, $nome, $codigo, 'deletar_conta');

    if (!$enviado) {
        echo json_encode(['ok' => false, 'erro' => 'Não foi possível enviar o e-mail. Tente novamente.']);
        exit;
    }

    $_SESSION['deletar_codigo_enviado'] = true;

    echo json_encode(['ok' => true]);
    exit;
}

if ($acao === 'confirmar_exclusao') {

    exigirMetodo('POST');

    if (empty($_POST['confirmar']) || empty($_POST['codigo'])) {
        $_SESSION['mensagem'] = 'Dados inválidos. Tente novamente.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../usuario.php');
        exit;
    }

    if (empty($_SESSION['deletar_codigo_enviado'])) {
        $_SESSION['mensagem'] = 'Ação não autorizada.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../usuario.php');
        exit;
    }

    $id = $_SESSION['id'] ?? null;

    if (!$id) {
        $_SESSION['mensagem'] = 'Sessão expirada. Faça login novamente.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../index.php');
        exit;
    }

    $codigoDigitado = trim($_POST['codigo']);

    $usuario = request("usuarios?id=eq.$id&select=email,codigo_verificacao,codigo_criado_em");

    if (empty($usuario) || isset($usuario['error'])) {
        $_SESSION['mensagem'] = 'Usuário não encontrado.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../usuario.php');
        exit;
    }

    $u = $usuario[0];
    $codigoSalvo = (string)($u['codigo_verificacao'] ?? '');
    $criadoEm = $u['codigo_criado_em'] ?? null;

    if ($criadoEm && (time() - strtotime($criadoEm)) / 60 > 15) {
        request("usuarios?id=eq.$id", 'PATCH', ['codigo_verificacao' => null]);
        $_SESSION['mensagem'] = 'O código expirou. Solicite um novo na página de perfil.';
        $_SESSION['tipo']     = 'aviso';
        header('Location: ../usuario.php');
        exit;
    }

    if ($codigoSalvo !== $codigoDigitado) {
        $_SESSION['mensagem'] = 'Código incorreto. Verifique seu e-mail e tente novamente.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../usuario.php');
        exit;
    }

    // 1. Avaliações feitas pelo usuário
    request("avaliacoes?id_cliente=eq.{$id}", 'DELETE');

    // 2. Contratos como cliente
    request("contratados?id_cliente=eq.{$id}", 'DELETE');

    // 3. Contratos dos serviços que ele prestava
    $servicos = request("servicos?id_prestador=eq.{$id}&select=id");
    if (!empty($servicos) && !isset($servicos['error'])) {
        foreach ($servicos as $s) {
            request("contratados?id_servico=eq.{$s['id']}", 'DELETE');
        }
    }

    // 4. Serviços anunciados
    request("servicos?id_prestador=eq.{$id}", 'DELETE');

    // 5. Avisos criados (síndico)
    request("avisos?id_usuario=eq.{$id}", 'DELETE');

    // 6. Registro do usuário
    request("usuarios?id=eq.{$id}", 'DELETE');

    // Destrói a sessão por completo
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

    $_SESSION['mensagem'] = 'Sua conta foi excluída com sucesso. Sentiremos sua falta.';
    $_SESSION['tipo']     = 'sucesso';
    header('Location: ../index.php');
    exit;
}

$_SESSION['mensagem'] = 'Ação inválida.';
$_SESSION['tipo']     = 'erro';
header('Location: ../usuario.php');
exit;