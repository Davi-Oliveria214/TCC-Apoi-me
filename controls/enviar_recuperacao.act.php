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

$agora = date('Y-m-d H:i:sO');

request("usuarios?email=eq.$email", "PATCH", [
    "codigo_verificacao" => $codigo,
    "codigo_criado_em" => $agora
]);

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

    $email_url = $_ENV['EMAIL_URL'];
    $link = $email_url . '?email=' . urlencode($email) . '&codigo=' . $codigo;

    $mail->setFrom($_ENV['EMAIL_APP'], 'Apoie-me Condomínios');
    $mail->addAddress($email, $usuario[0]['nome']);
    $mail->isHTML(true);
    $mail->Subject = 'Recuperação de Senha - Apoie-me';
    $mail->Body = "<div style='font-family: Arial, sans-serif;'>
            <h2>Recuperação de Senha</h2>
            <p>Olá <b>{$usuario[0]['nome']}</b>,</p>
            <p>Você solicitou a recuperação de senha. Seu código é: <b>$codigo</b></p>
            <p>Para prosseguir, clique no botão abaixo:</p>
            <a href='$link' style='background: #2c3e50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Redefinir minha Senha</a>
            <p>Ou copie e cole este link no navegador:<br>$link</p>
        </div>";

    $mail->send();

    $_SESSION['email_verificar'] = $email;
    $_SESSION['fluxo'] = 'recuperacao';
    header("Location: ../aviso_senha.php");
} catch (Exception $e) {
    $_SESSION["mensagem"] = "Erro ao enviar e-mail: {$mail->ErrorInfo}";
    header("Location: ../esqueci_senha.php");
}
