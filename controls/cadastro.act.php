<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../util/enviar_email.php');

$nome  = htmlspecialchars(trim($_POST['nome']));
$email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
$senha = $_POST['senha'];
$rptSenha = $_POST['rptSenha'];
$tipo_usuario = $_POST['tipo_usuario'] ?? null;
$cnpj_condominio = $_POST['cnpj_condominio'] ?? null;

if (!$email) {
    $_SESSION["mensagem"] = "Email inválido.";
    header("Location: ../cadastro.php");
    exit;
}

/* VALIDAÇÃO SENHA */
if (strlen($senha) < 8) {
    $_SESSION["mensagem"] = "A senha deve ter no mínimo 8 caracteres.";
    header("Location: ../cadastro.php");
    exit;
}

if ($senha !== $rptSenha) {
    $_SESSION["mensagem"] = "As senhas não coincidem.";
    header("Location: ../cadastro.php");
    exit;
}

if (
    !preg_match('/[A-Z]/', $senha) ||
    !preg_match('/[a-z]/', $senha) ||
    !preg_match('/[0-9]/', $senha) ||
    !preg_match('/[\W]/', $senha)
) {
    $_SESSION["mensagem"] = "Senha precisa ter: maiúscula, minúscula, número e símbolo.";
    header("Location: ../cadastro.php");
    exit;
}

// evitar senha com nome/email
if (stripos($senha, $nome) !== false || stripos($senha, $email) !== false) {
    $_SESSION["mensagem"] = "Senha não pode conter seu nome ou email.";
    header("Location: ../cadastro.php");
    exit;
}

/* GERAR DADOS */
$senhaHash = password_hash($senha, PASSWORD_BCRYPT);
$codigo = random_int(100000, 999999);
$img  = "../icon/user.png";

/* VERIFICAR EMAIL EXISTENTE */
$sql_email = request("usuarios?email=eq.$email&select=id,email_verificado", "GET");

if (!empty($sql_email) && !isset($sql_email['error'])) {

    if ($sql_email[0]['email_verificado'] == true) {
        $_SESSION["mensagem"] = "Email já cadastrado.";
        header("Location: ../login.php");
        exit;
    } else {
        $agora = date('Y-m-d H:i:sO');

        request("usuarios?email=eq.$email", "PATCH", [
            "codigo_verificacao" => $codigo,
            "codigo_criado_em" => $agora
        ]);

        $_SESSION['email_verificar'] = $email;
        enviarEmail($email, $nome, $codigo, 'cadastro');

        $_SESSION["mensagem"] = "Novo código enviado para seu e-mail.";
        header("Location: ../verificar_acesso.php?etapa=aviso");
        exit;
    }
}

/* VALIDAR CNPJ */
$dadosCondominio = null;
if ($tipo_usuario === 'sindico') {
    $dadosCondominio = validarCNPJ($cnpj_condominio);
}

/* CADASTRAR */
cadastrar($nome, $email, $senhaHash, $codigo, $img, $tipo_usuario, $dadosCondominio);