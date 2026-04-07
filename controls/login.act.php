<?php
session_start();
require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../util/enviar_email.php');

// 1. Validação de campos vazios
if (
    empty($_POST['email']) ||
    empty($_POST['senha']) ||
    empty($_POST['chave'])
) {
    $_SESSION["mensagem"] = "Preencha todos os campos.";
    header("Location: ../login.php");
    exit;
}

$email = trim($_POST['email']);
$senha = $_POST['senha'];
$chave = trim($_POST['chave']);

// 2. Busca o usuário no banco
$sql = request("usuarios?email=eq." . urlencode($email) . "&select=*", "GET");

if (empty($sql) || isset($sql['error'])) {
    $_SESSION["mensagem"] = "E-mail não cadastrado.";
    header("Location: ../cadastro.php");
    exit;
}

$usuario = $sql[0];

// 3. VERIFICAÇÃO DE E-MAIL
if (!$usuario['email_verificado']) {
    $nome = $usuario['nome'];

    // Gerar código e pegar horario
    $codigo = rand(100000, 999999);
    $agora = date('Y-m-d H:i:sO');

    // Salvando no banco de dados
    request("usuarios?email=eq." . urlencode($email), "PATCH", [
        "codigo_verificacao" => $codigo,
        "codigo_criado_em" => $agora
    ]);

    $_SESSION['fluxo'] = 'cadastro';
    $_SESSION['email_verificar'] = $email;
    enviarEmail($email, $nome, $codigo);
    $_SESSION["mensagem"] = "Seu e-mail ainda não foi verificado. Verifique sua caixa de entrada.";
    header("Location: ../aviso_codigo.php");
    exit;
}

// 4. Validação da Senha
if (!password_verify($senha, $usuario['senha'])) {
    $_SESSION["mensagem"] = "Senha incorreta.";
    header("Location: ../login.php");
    exit;
}

// 5. Validação da Chave do Condomínio / Buscamos se o código do condomínio existe
$sqlChave = request("condominios?codigo=eq." . urlencode($chave) . "&select=id", "GET");

if (empty($sqlChave) || isset($sqlChave['error'])) {
    $_SESSION["mensagem"] = "Chave de acesso ao condomínio inválida.";
    header("Location: ../login.php");
    exit;
}

// 6. Vinculação do usuário ao condomínio (PATCH)
$dadosUpdate = ["codigo" => (int)$chave];
$update = request("usuarios?id=eq.{$usuario['id']}", "PATCH", $dadosUpdate);

if (isset($update['error'])) {
    $_SESSION["mensagem"] = "Erro ao vincular condomínio. Tente novamente.";
    header("Location: ../login.php");
    exit;
}

// 7. Autenticação com sucesso - Criação da Sessão
$_SESSION["id"] = $usuario['id'];
$_SESSION["nome"] = $usuario['nome'];
$_SESSION["login"] = true;
$_SESSION["condominio_id"] = $chave;

// Limpa mensagens de erro anteriores
unset($_SESSION["mensagem"]);
header("Location: ../servicos.php");
exit;