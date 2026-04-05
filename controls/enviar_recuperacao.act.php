<?php
session_start();
require_once(__DIR__ . '/../conexao.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = trim($_POST['email']);
$codigo = rand(100000, 999999);

$usuario = request("usuarios?email=eq.$email&select=id,nome", "GET");
if (empty($usuario) || isset($usuario['error'])) {
    $_SESSION["mensagem"] = "Este e-mail não está cadastrado.";
    header("Location: ../esqueci_senha.php");
    exit;
}

request("usuarios?email=eq.$email", "PATCH", ["codigo_verificacao" => $codigo]);

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['EMAIL_APP'];
    $mail->Password   = $_ENV['SENHA_APP'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';
    $mail->SMTPOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true));

    $mail->setFrom($_ENV['EMAIL_APP'], 'Apoie-me Condomínios');
    $mail->addAddress($email, $usuario[0]['nome']);
    $mail->isHTML(true);
    $mail->Subject = 'Recuperação de Senha - Apoie-me';
    $mail->Body    = "Olá <b>{$usuario[0]['nome']}</b>, seu código de recuperação é: <h2>$codigo</h2>";

    $mail->send();

    $_SESSION['email_verificar'] = $email;
    $_SESSION['fluxo'] = 'recuperacao';
    header("Location: ../codigo_verificar.php");
} catch (Exception $e) {
    $_SESSION["mensagem"] = "Erro ao enviar e-mail: {$mail->ErrorInfo}";
    header("Location: ../esqueci_senha.php");
}