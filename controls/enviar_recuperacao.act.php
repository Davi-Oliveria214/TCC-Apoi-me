<?php
session_start();
require_once(__DIR__ . '/../conexao.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = trim($_POST['email']);
$codigo = rand(100000, 999999);

$usuario = request("usuarios?email=eq." . urlencode($email) . "&select=id,nome", "GET");

if (empty($usuario) || isset($usuario['error'])) {
    $_SESSION["mensagem"] = "Este e-mail não está cadastrado.";
    header("Location: ../esqueci_senha.php");
    exit;
}

$agora = date('Y-m-d H:i:sO');

$res_update = request("usuarios?email=eq." . urlencode($email), "PATCH", [
    "codigo_verificacao" => $codigo,
    "codigo_criado_em" => $agora
]);

if (isset($res_update['error'])) {
    $_SESSION["mensagem"] = "Erro ao processar solicitação no servidor.";
    header("Location: ../esqueci_senha.php");
    exit;
}

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

    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];

    $email_url = $_ENV['EMAIL_URL']; 
    $link = $email_url . '?email=' . urlencode($email);

    $mail->setFrom($_ENV['EMAIL_APP'], 'Apoie-me Condomínios');
    $mail->addAddress($email, $usuario[0]['nome']);

    $mail->isHTML(true);
    $mail->Subject = 'Recuperação de Senha - Apoie-me';
    
    $mail->Body = "
        <div style='font-family: Arial, sans-serif; color: #333; line-height: 1.6;'>
            <h2 style='color: #2c3e50;'>Recuperação de Senha</h2>
            <p>Olá <b>{$usuario[0]['nome']}</b>,</p>
            <p>Você solicitou a redefinição de sua senha. Use o código abaixo na página de verificação:</p>
            
            <div style='background: #f4f4f4; padding: 15px; text-align: center; border-radius: 8px; margin: 20px 0;'>
                <span style='font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #2c3e50;'>$codigo</span>
            </div>

            <p><strong>Importante:</strong> Este código é válido por apenas 15 minutos.</p>
            
            <p>Para prosseguir, clique no botão abaixo para abrir a página de validação:</p>
            <a href='$link' style='background: #2c3e50; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;'>Redefinir minha Senha</a>
            
            <p style='margin-top: 30px; font-size: 12px; color: #777;'>
                Se você não solicitou esta alteração, por favor ignore este e-mail.<br>
                Caso o botão não funcione, copie este link: $link
            </p>
        </div>";
    $mail->AltBody = "Olá {$usuario[0]['nome']}, seu código de recuperação é: $codigo. Acesse $link para redefinir.";
    $mail->send();

    $_SESSION['email_verificar'] = $email;
    $_SESSION['fluxo'] = 'recuperacao';

    header("Location: ../aviso_codigo.php");
    exit;
} catch (Exception $e) {
    $_SESSION["mensagem"] = "Erro ao enviar e-mail: {$mail->ErrorInfo}";
    header("Location: ../esqueci_senha.php");
    exit;
}