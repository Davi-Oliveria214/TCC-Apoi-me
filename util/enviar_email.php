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
        $link = $_ENV['EMAIL_URL'] . "?email=" . urlencode($email) . "&codigo=" . $codigo . "&tipo_codigo=" . trim($fluxo);

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
            "<h2>Bem-vindo, Administrador!</h2>
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
