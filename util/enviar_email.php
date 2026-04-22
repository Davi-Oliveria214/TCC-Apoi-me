<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviarEmail($email, $nome, $codigo, $fluxo = 'cadastro', $chave = '')
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
            $link = $_ENV['EMAIL_URL'] . "?email=" . urlencode($email) . "&codigo=" . $codigo . "&tipo_codigo=" . trim($fluxo);
        }

        $corpoEmail = "";
        if (trim($fluxo) === 'cadastro') {
            $corpoEmail = "<h2>Bem-vindo ao Apoie-me!</h2>
                          <p>Olá <b>$nomeEscapado</b>, seu código de ativação é: <b>$codigo</b></p>
                          <a href='$link'>Clique aqui para validar sua conta</a>";
        } else if (trim($fluxo) === 'recuperar') {
            $corpoEmail = "<h2>Recuperação de Senha</h2>
                          <p>Olá <b>$nomeEscapado</b>, seu código de segurança é: <b>$codigo</b></p>
                          <p>Use o link para prosseguir: <a href='$link'>Redefinir Senha</a></p>";
        } else if (trim($fluxo) === 'chave') {
            $corpoEmail = "<h2>Bem-vindo, Administrador!</h2>
                  <p>Olá <b>$nomeEscapado</b>, sua conta foi criada com sucesso.</p>
                  <p>Seu código de ativação é: <b>$codigo</b></p>
                  <p><b>Chave de Acesso do seu Condomínio:</b> <span style='font-size: 20px; color: #b0822b;'>$chave</span></p>
                  <p>Compartilhe esta chave apenas com os moradores do seu prédio.</p>
                  <p><a href='$link'>Clique aqui para validar sua conta e começar</a></p>";
        } else {
            $corpoEmail = "<h2>Seu código de verificação Apoie-me</h2>
                  <p>Olá $nomeEscapado, seu código é: <b>$codigo</b></p>
                  <p>Use o link para prosseguir: <a href='$link'>Link código</a></p>";
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
