<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviarEmail($email, $nome, $codigo, $fluxo = 'cadastro', $chave = '', $novo = '')
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
        if (isset($codigo)) {
            $link = $_ENV['EMAIL_URL'] . "?email=" . urlencode($email) . "&codigo=" . $codigo . "&tipo_codigo=" . trim($fluxo) . (!empty($novo) ? "&novo_email=" . urlencode($novo) : "");
        }

        $mail->Body = textosEmails($nome, $codigo, $link, $fluxo, $chave);
        $mail->AltBody = "Olá $nomeEscapado, seu código de verificação é: $codigo";

        $mail->send();

        return true;
    } catch (Exception $e) {
        return false;
    }
}

function emailContato($email, $nome, $comentario)
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

        $mail->setFrom($_ENV['EMAIL_APP'], 'Formulário de Contato - Apoie-me');
        $mail->addAddress($_ENV['EMAIL_APP'], 'Admin Apoie-me');
        $mail->addReplyTo($email, $nome);
        $mail->isHTML(true);
        $mail->Subject = 'Novo Contato Recebido: ' . $nome;

        $nomeEscapado = htmlspecialchars($nome);
        $comentarioEscapado = nl2br(htmlspecialchars($comentario));

        $mail->Body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 2px solid #b0822b; border-radius: 10px; overflow: hidden;'>
                <div style='background-color: #2b3d2c; padding: 20px; text-align: center; color: #ffffff;'>
                    <h2 style='margin: 0; color: #b0822b;'>Novo Contato - Apoie-me</h2>
                </div>
                <div style='padding: 20px; background-color: #f9f9f9; color: #333;'>
                    <p><b>Nome do Usuário:</b> $nomeEscapado</p>
                    <p><b>E-mail de Contato:</b> $email</p>
                    <hr style='border: 0; border-top: 1px solid #ddd; margin: 20px 0;'>
                    <p><b>Mensagem:</b></p>
                    <div style='background-color: #ffffff; padding: 15px; border-left: 4px solid #b0822b; border-radius: 4px;'>
                        $comentarioEscapado
                    </div>
                </div>
            </div>
        ";

        $mail->AltBody = "Novo contato recebido de $nome ($email).\nMensagem: $comentario";

        $mail->send();

        $_SESSION["mensagem"] = "Sua mensagem foi enviada com sucesso!";
    } catch (Exception $e) {
        $_SESSION["mensagem"] = "Erro ao enviar contato. Tente novamente mais tarde.";
    }
}

function textosEmails($nome, $codigo, $link, $fluxo, $chave = '')
{
    $nomeEscapado = htmlspecialchars($nome);

    switch (trim($fluxo)) {
        case 'cadastro':
            return "<h2>Bem-vindo ao Apoie-me!</h2>
                    <p>Olá <b>$nomeEscapado</b>, seu código de ativação é: <b>$codigo</b></p>
                    <a href='$link'>Clique aqui para validar sua conta</a>";

        case 'recuperar':
            return "<h2>Recuperação de Senha</h2>
                    <p>Olá <b>$nomeEscapado</b>, seu código de segurança é: <b>$codigo</b></p>
                    <p>Use o link: <a href='$link'>Redefinir Senha</a></p>";
        case 'chave':
            return "<h2>Bem-vindo, Administrador!</h2>
                    <p>Olá <b>$nomeEscapado</b>, sua conta foi criada.</p>
                    <p>Código: <b>$codigo</b></p>
                    <p><b>Chave:</b> <span style='font-size:20px;color:#b0822b;'>$chave</span></p>
                    <a href='$link'>Validar conta</a>";
        case 'alterar_email':
            return "<div style='font-family: sans-serif; max-width: 500px; color: #333;'>
            <h2 style='color: #2e4a3b;'>Confirmação de Novo E-mail</h2>
            <p>Olá, <b>$nomeEscapado</b>!</p>
            <p>Recebemos uma solicitação para vincular este endereço de e-mail à sua conta no <b>Apoie ME</b>.</p>
            
            <div style='background: #fdfaf3; border: 1px dashed #b0822b; padding: 20px; text-align: center; border-radius: 12px; margin: 20px 0;'>
                <p style='margin-bottom: 10px; font-size: 14px;'>Seu código de validação é:</p>
                <span style='font-size: 32px; font-weight: bold; color: #b0822b; letter-spacing: 5px;'>$codigo</span>
            </div>

            <p>Se preferir, basta clicar no botão abaixo para confirmar a alteração:</p>
            <a href='$link' style='display: inline-block; padding: 14px 25px; background-color: #2e4a3b; color: #fdfaf3; text-decoration: none; border-radius: 8px; font-weight: bold;'>Validar Novo E-mail</a>

            <hr style='border: 0; border-top: 1px solid #eee; margin-top: 30px;'>
            <p style='font-size: 12px; color: #888;'>
                Se você não solicitou essa alteração, nenhuma ação é necessária. Outra pessoa pode ter digitado seu e-mail por engano.
            </p>
        </div>";
        default:
            return "<h2>Código de verificação</h2>
                    <p>Olá $nomeEscapado, seu código é: <b>$codigo</b></p>
                    <a href='$link'>Abrir link</a>";
    }
}
