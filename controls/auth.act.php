<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../util/enviar_email.php');

$acao = $_POST['acao'] ?? '';

if ($acao === 'login') {

    if (empty($_POST['email']) || empty($_POST['senha']) || empty($_POST['chave'])) {
        $_SESSION['mensagem'] = 'Preencha todos os campos.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../login.php');
        exit;
    }

    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $chave = trim($_POST['chave']);

    $sql = request('usuarios?email=eq.' . urlencode($email) . '&select=*', 'GET');

    if (empty($sql) || isset($sql['error'])) {
        $_SESSION['mensagem'] = 'E-mail ou senha inválidos.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../login.php');
        exit;
    }

    $usuario = $sql[0];

    if (!$usuario['email_verificado']) {
        $codigo = random_int(100000, 999999);
        $agora  = date('Y-m-d H:i:sO');

        request('usuarios?email=eq.' . urlencode($email), 'PATCH', [
            'codigo_verificacao' => $codigo,
            'codigo_criado_em'   => $agora,
        ]);

        $_SESSION['email_verificar'] = $email;
        enviarEmail($email, $usuario['nome'], $codigo, 'cadastro');

        $_SESSION['mensagem'] = 'Seu e-mail ainda não foi verificado. Verifique sua caixa de entrada.';
        $_SESSION['tipo']     = 'aviso';
        header('Location: ../verificar_acesso.php?etapa=aviso&tipo_envio=validar');
        exit;
    }

    if (!password_verify($senha, $usuario['senha'])) {
        $_SESSION['mensagem'] = 'E-mail ou senha inválidos.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../login.php');
        exit;
    }

    $sqlChave = request('condominios?codigo=eq.' . urlencode($chave) . '&select=id', 'GET');

    if (empty($sqlChave) || isset($sqlChave['error'])) {
        $_SESSION['mensagem'] = 'Chave de acesso ao condomínio inválida.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../login.php');
        exit;
    }

    $update = request("usuarios?id=eq.{$usuario['id']}", 'PATCH', ['codigo' => (int)$chave]);

    if (isset($update['error'])) {
        $_SESSION['mensagem'] = 'Erro ao vincular condomínio. Tente novamente.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../login.php');
        exit;
    }

    $_SESSION['id']            = $usuario['id'];
    $_SESSION['nome']          = $usuario['nome'];
    $_SESSION['login']         = true;
    $_SESSION['condominio_id'] = $chave;
    unset($_SESSION['mensagem']);

    header('Location: ../servicos.php');
    exit;
}

// AÇÃO 2 — CADASTRO
if ($acao === 'cadastro') {

    $nome            = htmlspecialchars(trim($_POST['nome'] ?? ''));
    $email           = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $senha           = $_POST['senha']           ?? '';
    $rptSenha        = $_POST['rptSenha']        ?? '';
    $tipo_usuario    = $_POST['tipo_usuario']    ?? 'morador';
    $cnpj_condominio = $_POST['cnpj_condominio'] ?? null;

    if (!$email) {
        $_SESSION['mensagem'] = 'E-mail inválido.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../cadastro.php');
        exit;
    }

    if (strlen($senha) < 8) {
        $_SESSION['mensagem'] = 'A senha deve ter no mínimo 8 caracteres.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../cadastro.php');
        exit;
    }

    if ($senha !== $rptSenha) {
        $_SESSION['mensagem'] = 'As senhas não coincidem.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../cadastro.php');
        exit;
    }

    if (
        !preg_match('/[A-Z]/', $senha) ||
        !preg_match('/[a-z]/', $senha) ||
        !preg_match('/[0-9]/', $senha) ||
        !preg_match('/[\W]/',  $senha)
    ) {
        $_SESSION['mensagem'] = 'Senha precisa ter: maiúscula, minúscula, número e símbolo.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../cadastro.php');
        exit;
    }

    if (stripos($senha, $nome) !== false || stripos($senha, $email) !== false) {
        $_SESSION['mensagem'] = 'Senha não pode conter seu nome ou e-mail.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../cadastro.php');
        exit;
    }

    $senhaHash = password_hash($senha, PASSWORD_BCRYPT);
    $codigo    = random_int(100000, 999999);
    $img       = '../icon/user.png';

    $sqlEmail = request("usuarios?email=eq.$email&select=id,email_verificado", 'GET');

    if (!empty($sqlEmail) && !isset($sqlEmail['error'])) {
        if ($sqlEmail[0]['email_verificado']) {
            $_SESSION['mensagem'] = 'E-mail já cadastrado.';
            $_SESSION['tipo']     = 'aviso';
            header('Location: ../login.php');
            exit;
        }

        $agora = date('Y-m-d H:i:sO');
        request("usuarios?email=eq.$email", 'PATCH', [
            'codigo_verificacao' => $codigo,
            'codigo_criado_em'   => $agora,
        ]);

        $_SESSION['email_verificar'] = $email;
        enviarEmail($email, $nome, $codigo, 'cadastro');

        $_SESSION['mensagem'] = 'Novo código enviado para seu e-mail.';
        $_SESSION['tipo']     = 'info';
        header('Location: ../verificar_acesso.php?etapa=aviso');
        exit;
    }

    $dadosCondominio = null;
    if ($tipo_usuario === 'sindico') {
        $dadosCondominio = validarCNPJ($cnpj_condominio);
    }

    cadastrar($nome, $email, $senhaHash, $codigo, $img, $tipo_usuario, $dadosCondominio);
    exit;
}

if ($acao === 'contato') {

    if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['comentario'])) {
        $_SESSION['mensagem'] = 'Preencha todos os campos obrigatórios.';
        $_SESSION['tipo']     = 'aviso';
        header('Location: ../contato.php');
        exit;
    }

    $nome       = htmlspecialchars(trim($_POST['nome']));
    $email      = trim($_POST['email']);
    $comentario = trim($_POST['comentario']);
    $tipo       = $_POST['tipo'] ?? 'Dúvida';
    $nota       = (int) ($_POST['nota'] ?? 0);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['mensagem'] = 'E-mail inválido.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../contato.php');
        exit;
    }

    $enviado = emailContato($email, $nome, $comentario, $tipo, $nota);

    if ($enviado) {
        $_SESSION['mensagem'] = 'Sua mensagem foi enviada com sucesso!';
        $_SESSION['tipo']     = 'sucesso';
    } else {
        $_SESSION['mensagem'] = 'Erro ao enviar. Tente novamente mais tarde.';
        $_SESSION['tipo']     = 'erro';
    }

    header('Location: ../contato.php');
    exit;
}

$_SESSION['mensagem'] = 'Ação inválida.';
$_SESSION['tipo']     = 'erro';
header('Location: ../index.php');
exit;