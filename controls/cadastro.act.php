<?php
session_start();
require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../util/enviar_email.php');

$nome  = trim($_POST['nome']);
$email = trim($_POST['email']);
$telefone = $_POST['telefone'];
$senhaPlana = $_POST['senha'];
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

$sql_tel = request("usuarios?telefone=eq.$telefone&select=id", "GET");
if (!empty($sql_tel) && !isset($sql_tel['error'])) {
    $_SESSION["mensagem"] = "Telefone já cadastrado.";
    header("Location: ../cadastro.php");
    exit;
}

cadastrar($nome, $email, $telefone, $senhaHash, $codigo, $img);

function cadastrar($nome, $email, $telefone, $senhaHash, $codigo, $img)
{
    $_SESSION['email_verificar'] = $email;
    enviarEmail($email, $nome, $codigo, 'cadastro');
    $agora = date('Y-m-d H:i:sO');
    $dados = [
        "nome" => $nome,
        "email" => $email,
        "telefone" => $telefone,
        "senha" => $senhaHash,
        "codigo_verificacao" => $codigo,
        "email_verificado" => false,
        "imagem" => $img,
        "codigo_criado_em" => $agora
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