<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../util/enviar_email.php');

// Validação de campos vazios
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

// Busca o usuário no banco
$sql = request("usuarios?email=eq." . urlencode($email) . "&select=*", "GET");

if (empty($sql) || isset($sql['error'])) {
    $_SESSION["mensagem"] = "Email ou senha inválidos.";
    header("Location: ../cadastro.php");
    exit;
}

$usuario = $sql[0];

// VERIFICAÇÃO DE E-MAIL
if (!$usuario['email_verificado']) {
    $nome = $usuario['nome'];

    // Gerar código e pegar horario
    $codigo = random_int(100000, 999999);
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
    header("Location: ../verificar_acesso.php?etapa=aviso");
    exit;
}

// Validação da Senha
if (!password_verify($senha, $usuario['senha'])) {
    $_SESSION["mensagem"] = "Email ou senha inválidos.";
    header("Location: ../login.php");
    exit;
}

// Validação da Chave do Condomínio / Buscamos se o código do condomínio existe
$sqlChave = request("condominios?codigo=eq." . urlencode($chave) . "&select=id", "GET");

if (empty($sqlChave) || isset($sqlChave['error'])) {
    $_SESSION["mensagem"] = "Chave de acesso ao condomínio inválida.";
    header("Location: ../login.php");
    exit;
}

// Vinculação do usuário ao condomínio (PATCH)
$dadosUpdate = ["codigo" => (int)$chave];
$update = request("usuarios?id=eq.{$usuario['id']}", "PATCH", $dadosUpdate);

if (isset($update['error'])) {
    $_SESSION["mensagem"] = "Erro ao vincular condomínio. Tente novamente.";
    header("Location: ../login.php");
    exit;
}

// Autenticação com sucesso - Criação da Sessão
$_SESSION["id"] = $usuario['id'];
$_SESSION["nome"] = $usuario['nome'];
$_SESSION["login"] = true;
$_SESSION["condominio_id"] = $chave;

// Limpa mensagens de erro anteriores
unset($_SESSION["mensagem"]);
header("Location: ../servicos.php");
exit;