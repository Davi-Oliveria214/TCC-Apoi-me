<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviarEmail($email, $nome, $codigo)
{
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

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->setFrom($_ENV['EMAIL_APP'], 'Apoie-me Condomínios');
        $mail->addAddress($email, $nome);

        $mail->isHTML(true);
        $mail->Subject = 'Confirmação - Apoie-me Condomínios';

        $nomeEscapado = htmlspecialchars($nome);
        $link = $_ENV['EMAIL_URL'] . "?email=" . urlencode($email) . "&codigo=" . $codigo;

        $corpoEmail = "";
        if (isset($_SESSION['fluxo']) && $_SESSION['fluxo'] == 'cadastro') {
            $corpoEmail = "<h2>Bem-vindo ao Apoie-me!</h2>
                          <p>Olá <b>$nomeEscapado</b>, seu código de ativação é: <b>$codigo</b></p>
                          <a href='$link'>Clique aqui para validar sua conta</a>";
        } else if (isset($_SESSION['fluxo']) && $_SESSION['fluxo'] == 'recuperar') {
            $corpoEmail = "<h2>Recuperação de Senha</h2>
                          <p>Olá <b>$nomeEscapado</b>, seu código de segurança é: <b>$codigo</b></p>
                          <p>Use o link para prosseguir: <a href='$link'>Redefinir Senha</a></p>";
        }
        $mail->Body = $corpoEmail;
        $mail->AltBody = "Olá $nomeEscapado, seu código de verificação é: $codigo";

        $mail->send();
    } catch (Exception $e) {
        $_SESSION["mensagem"] = "Erro ao enviar email";
        header("Location: ../cadastro.php");
        exit;
    }
}