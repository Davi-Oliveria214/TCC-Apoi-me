<?php
session_start();
require_once(__DIR__ . '/../conexao.php');
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email_app = $_ENV['EMAIL_APP'];
$senha_app = $_ENV['SENHA_APP'];

$nome  = trim($_POST['nome']);
$email = trim($_POST['email']);
$telefone = $_POST['telefone'];
$senhaPlana = $_POST['senha'];
$senhaHash = password_hash($senhaPlana, PASSWORD_BCRYPT);
$img  = "../icon/user.png";
$codigo = rand(100000, 999999);

$sql_email = request("usuarios?email=eq.$email&select=id", "GET");
if (!empty($sql_email) && !isset($sql_email['error'])) {
    $_SESSION["mensagem"] = "Email já cadastrado.";
    header("Location: ../cadastro.php");
    exit;
}

$sql_tel = request("usuarios?telefone=eq.$telefone&select=id", "GET");
if (!empty($sql_tel) && !isset($sql_tel['error'])) {
    $_SESSION["mensagem"] = "Telefone já cadastrado.";
    header("Location: ../cadastro.php");
    exit;
}

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $email_app;
    $mail->Password   = $senha_app;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';

    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    $mail->setFrom($email_app, 'Apoie-me Condomínios');
    $mail->addAddress($email, $nome);

    $mail->isHTML(true);
    $mail->Subject = 'Código de Verificação - Cadastro';

    $nomeEscapado = htmlspecialchars($nome);
    $mail->Body = "Olá <b>$nomeEscapado</b>, seu código de verificação é: <h2 style='color: #2c3e50;'>$codigo</h2>";
    $mail->AltBody = "Olá $nomeEscapado, seu código de verificação é: $codigo";

    $mail->send();

    $dados = [
        "nome" => $nome,
        "email" => $email,
        "telefone" => $telefone,
        "senha" => $senhaHash,
        "codigo_verificacao" => $codigo,
        "email_verificado" => false,
        "imagem" => $img
    ];

    request("usuarios", "POST", $dados);

    $_SESSION['email_verificar'] = $email;

    header("Location: ../codigo_verificar.php");
    exit;
} catch (Exception $e) {
    $_SESSION["mensagem"] = "Erro ao enviar e-mail: " . $mail->ErrorInfo;
    header("Location: ../cadastro.php");
    exit;
}
