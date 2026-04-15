<?php
session_start();
require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../util/enviar_email.php');

$nome  = trim($_POST['nome']);
$email = trim($_POST['email']);
$senhaPlana = $_POST['senha'];
$tipo_usuario = $_POST['tipo_usuario'];
$cnpj_condominio = $_POST['cnpj_condominio'];
$senhaHash = password_hash($senhaPlana, PASSWORD_BCRYPT);
$img  = "../icon/user.png";
$codigo = rand(100000, 999999);

$sql_email = request("usuarios?email=eq.$email&select=id,email_verificado", "GET");
if (!empty($sql_email) && !isset($sql_email['error'])) {
    if ($sql_email[0]['email_verificado'] == true) {
        $_SESSION["mensagem"] = "Email já cadastrado e verificado. Vá para o login.";
        header("Location: ../login.php");
        exit;
    } else {
        $agora = date('Y-m-d H:i:sO');
        $res = request("usuarios?email=eq.$email", "PATCH", [
            "codigo_verificacao" => $codigo,
            "codigo_criado_em" => $agora
        ]);

        if (isset($res['error'])) {
            $_SESSION["mensagem"] = "Erro ao enviar código";
            header("Location: ../cadastro.php");
            exit;
        }

        $_SESSION['email_verificar'] = $email;
        enviarEmail($email, $nome, $codigo, 'cadastro');
        $_SESSION["mensagem"] = "Cadastro pendente: Enviamos um novo código para seu e-mail.";
        header("Location: ../aviso_codigo.php");
        exit;
    }
}

$dadosCondominio = null;
if ($tipo_usuario === 'sindico') {
    $dadosCondominio = validarCNPJ($cnpj_condominio);
    if (!$dadosCondominio) {
        $_SESSION["mensagem"] = "CNPJ inválido ou não pertence a um condomínio.";
        header("Location: ../cadastro.php");
        exit;
    }
}

function validarCNPJ($cnpj)
{
    $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
    if (strlen($cnpj) != 14) {
        $_SESSION["mensagem"] = "Erro: CNPJ com tamanho errado. Recebido: " . strlen($cnpj) . " dígitos.";
        header("Location: ../cadastro.php");
        exit;
    }

    $url = "https://brasilapi.com.br/api/cnpj/v1/" . $cnpj;

    $opts = ["http" => ["ignore_errors" => true, "header" => "User-Agent: PHP\r\n"]];
    $context = stream_context_create($opts);
    $response = file_get_contents($url, false, $context);

    if ($response === false) {
        $_SESSION["mensagem"] = "Erro: Não foi possível conectar à Brasil API. Verifique sua internet ou se o InfinityFree bloqueou a saída.";
        header("Location: ../cadastro.php");
        exit;
    }

    $dados = json_decode($response, true);

    if (isset($dados['message'])) {
        $_SESSION["mensagem"] = "Erro retornado pela API: " . $dados['message'] . " (CNPJ testado: $cnpj)";
        header("Location: ../cadastro.php");
        exit;
    }

    $nomeEmpresa = strtoupper($dados['razao_social']);

    if (strpos($nomeEmpresa, 'CONDOMINIO') !== false || strpos($nomeEmpresa, 'CONDOM') !== false) {
        return $dados;
    }

    $_SESSION["mensagem"] = "Erro: O CNPJ pertence a '" . $nomeEmpresa . "', mas não contém a palavra CONDOMINIO.";
    header("Location: ../cadastro.php");
    exit;
}

cadastrar($nome, $email, $senhaHash, $codigo, $img, $tipo_usuario, $dadosCondominio);

function cadastrar($nome, $email, $senhaHash, $codigo, $img, $tipo_usuario, $dadosCondominio)
{
    $cnpj = null;
    $chave = null;
    $em = "cadastro";

    if ($tipo_usuario === 'sindico' && $dadosCondominio) {
        $cnpj = $dadosCondominio['cnpj'];
        $chave = rand(1000, 9999);
        $novoCondo = [
            "nome" => $dadosCondominio['razao_social'],
            "cnpj_condominio" => $cnpj,
            "codigo" => $chave
        ];

        request("condominios", "POST", $novoCondo);
        $em = "chave";
    }

    $_SESSION['email_verificar'] = $email;
    enviarEmail($email, $nome, $codigo, $em, $chave);

    $agora = date('Y-m-d H:i:sO');

    $dados = [
        "nome" => $nome,
        "email" => $email,
        "senha" => $senhaHash,
        "codigo_verificacao" => $codigo,
        "email_verificado" => false,
        "imagem" => $img,
        "tipo_usuario" => $tipo_usuario,
        "codigo_criado_em" => $agora,
        "cnpj_vinculado" => $cnpj
    ];

    $res = request("usuarios", "POST", $dados);

    if (isset($res['error'])) {
        $_SESSION["mensagem"] = "Erro ao salvar os dados";
        header("Location: ../cadastro.php");
        exit;
    }

    header("Location: ../aviso_codigo.php");
    exit;
}