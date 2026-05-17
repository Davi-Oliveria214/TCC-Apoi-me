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

        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ]
        ];

        $mail->setFrom($_ENV['EMAIL_APP'], 'Apoie-me Condomínios');
        $mail->addAddress($email, $nome);
        $mail->isHTML(true);

        $assuntos = [
            'cadastro' => 'Bem-vindo ao Apoie-me — Confirme sua conta',
            'recuperar' => 'Redefinição de senha — Apoie-me',
            'chave' => 'Sua conta de síndico foi criada — Apoie-me',
            'alterar_email' => 'Confirmação de novo e-mail — Apoie-me',
            'deletar_conta' => 'Confirmação de exclusão de conta — Apoie-me',
        ];

        $mail->Subject = $assuntos[trim($fluxo)] ?? 'Verificação — Apoie-me';

        if (isset($codigo) && $fluxo !== 'deletar_conta') {
            $etapas = [
                'cadastro' => 'codigo',
                'recuperar' => 'codigo',
                'chave' => 'codigo',
                'alterar_email' => 'codigo',
            ];

            $etapa = $etapas[trim($fluxo)] ?? 'codigo';

            $baseUrl = rtrim($_ENV['EMAIL_URL'], '/');
            $sep = str_contains($baseUrl, '?') ? '&' : '?';

            $link = $baseUrl . $sep . 'etapa=' . $etapa
                . '&email=' . urlencode($email)
                . '&codigo=' . $codigo
                . '&tipo_codigo=' . trim($fluxo)
                . (!empty($novo) ? '&novo_email=' . urlencode($novo) : '');
        } else {
            $link = '';
        }

        $mail->Body    = textosEmails($nome, $codigo, $link, $fluxo, $chave);
        $mail->AltBody = altBodyTexto($nome, $codigo, $link, $fluxo, $chave);

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function emailContato($email, $nome, $comentario, $tipo, $nota)
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

        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ]
        ];

        $mail->setFrom($_ENV['EMAIL_APP'], 'Apoie-me — Formulário de Contato');
        $mail->addAddress($_ENV['EMAIL_APP'], 'Equipe Apoie-me');
        $mail->addReplyTo($email, $nome);
        $mail->isHTML(true);
        $mail->Subject = "[$tipo] Nova mensagem de $nome";

        $nomeEsc       = htmlspecialchars($nome);
        $emailEsc      = htmlspecialchars($email);
        $comentarioEsc = nl2br(htmlspecialchars($comentario));
        $tipoEsc       = htmlspecialchars($tipo);
        $notaStars     = !empty($nota) ? str_repeat('★', (int)$nota) . str_repeat('☆', 5 - (int)$nota) : '—';

        $mail->Body = _layout(
            'Nova mensagem recebida',
            "
            <p style='color:#555; font-size:15px; margin:0 0 20px;'>
                Você recebeu uma nova mensagem pelo formulário de contato do site.
            </p>

            <table style='width:100%; border-collapse:collapse; font-size:14px;'>
                <tr>
                    <td style='padding:10px 14px; background:#f4f0e8; border-radius:6px 6px 0 0; font-weight:bold; color:#2e4a3b; width:35%;'>Remetente</td>
                    <td style='padding:10px 14px; background:#fdfaf3; border-radius:6px 6px 0 0;'>$nomeEsc</td>
                </tr>
                <tr>
                    <td style='padding:10px 14px; background:#f4f0e8; font-weight:bold; color:#2e4a3b;'>E-mail</td>
                    <td style='padding:10px 14px; background:#fdfaf3;'><a href='mailto:$emailEsc' style='color:#b0822b;'>$emailEsc</a></td>
                </tr>
                <tr>
                    <td style='padding:10px 14px; background:#f4f0e8; font-weight:bold; color:#2e4a3b;'>Tipo</td>
                    <td style='padding:10px 14px; background:#fdfaf3;'>$tipoEsc</td>
                </tr>
                " . (!empty($nota) ? "
                <tr>
                    <td style='padding:10px 14px; background:#f4f0e8; font-weight:bold; color:#2e4a3b;'>Avaliação</td>
                    <td style='padding:10px 14px; background:#fdfaf3; font-size:18px; color:#b0822b;'>$notaStars</td>
                </tr>" : "") . "
            </table>

            <p style='font-weight:bold; color:#2e4a3b; margin:24px 0 8px;'>Mensagem:</p>
            <div style='background:#fff; border-left:4px solid #b0822b; padding:16px 20px; border-radius:0 8px 8px 0; color:#333; line-height:1.7;'>
                $comentarioEsc
            </div>

            <p style='margin-top:24px; font-size:13px; color:#999;'>
                Responda diretamente a este e-mail para entrar em contato com o usuário.
            </p>
            "
        );

        $mail->AltBody = "Nova mensagem de $nome ($email)\nTipo: $tipo\n\nMensagem:\n$comentario";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function textosEmails($nome, $codigo, $link, $fluxo, $chave = '')
{
    $nomeEsc = htmlspecialchars($nome);

    switch (trim($fluxo)) {

        case 'cadastro':
            return _layout(
                'Confirme sua conta',
                "
                <p style='color:#555; font-size:15px; line-height:1.7; margin:0 0 20px;'>
                    Olá, <strong style='color:#2e4a3b;'>$nomeEsc</strong>!<br>
                    Bem-vindo ao <strong>Apoie-me Condomínios</strong>. Estamos felizes em ter você por aqui.
                </p>
                <p style='color:#555; font-size:15px; line-height:1.7; margin:0 0 24px;'>
                    Para ativar sua conta, utilize o código abaixo ou clique no botão de confirmação:
                </p>
                " . _blococodigo($codigo) . "
                " . _botao($link, 'Confirmar minha conta') . "
                <p style='color:#999; font-size:13px; margin-top:24px;'>
                    O código é válido por <strong>30 minutos</strong>. Se você não criou uma conta no Apoie-me, ignore este e-mail.
                </p>
                "
            );

        case 'recuperar':
            return _layout(
                'Redefinição de senha',
                "
                <p style='color:#555; font-size:15px; line-height:1.7; margin:0 0 20px;'>
                    Olá, <strong style='color:#2e4a3b;'>$nomeEsc</strong>!<br>
                    Recebemos uma solicitação de redefinição de senha para a sua conta.
                </p>
                <p style='color:#555; font-size:15px; line-height:1.7; margin:0 0 24px;'>
                    Use o código abaixo ou clique no botão para criar uma nova senha:
                </p>
                " . _blococodigo($codigo) . "
                " . _botao($link, 'Redefinir minha senha') . "
                <p style='color:#999; font-size:13px; margin-top:24px;'>
                    O código expira em <strong>30 minutos</strong>. Se você não solicitou esta redefinição, sua senha permanece a mesma — nenhuma ação é necessária.
                </p>
                "
            );

        case 'chave':
            return _layout(
                'Conta de síndico criada',
                "
                <p style='color:#555; font-size:15px; line-height:1.7; margin:0 0 20px;'>
                    Olá, <strong style='color:#2e4a3b;'>$nomeEsc</strong>!<br>
                    Sua conta de síndico foi criada com sucesso no <strong>Apoie-me Condomínios</strong>.
                </p>
                <p style='color:#555; font-size:15px; margin:0 0 8px;'>
                    Primeiro, confirme sua conta com o código abaixo:
                </p>
                " . _blococodigo($codigo) . "
                <p style='color:#555; font-size:15px; margin:20px 0 8px;'>
                    Guarde também a <strong>chave do condomínio</strong> — ela será solicitada aos moradores no momento do cadastro:
                </p>
                <div style='text-align:center; background:#fdfaf3; border:2px dashed #b0822b; border-radius:12px; padding:20px; margin:0 0 24px;'>
                    <p style='margin:0 0 6px; font-size:13px; color:#888;'>Chave do Condomínio</p>
                    <span style='font-size:36px; font-weight:bold; color:#b0822b; letter-spacing:6px;'>$chave</span>
                </div>
                " . _botao($link, 'Confirmar minha conta') . "
                <p style='color:#999; font-size:13px; margin-top:24px;'>
                    O código de verificação expira em <strong>30 minutos</strong>.
                </p>
                "
            );

        case 'alterar_email':
            return _layout(
                'Confirmação de novo e-mail',
                "
                <p style='color:#555; font-size:15px; line-height:1.7; margin:0 0 20px;'>
                    Olá, <strong style='color:#2e4a3b;'>$nomeEsc</strong>!<br>
                    Recebemos uma solicitação para vincular este endereço de e-mail à sua conta no <strong>Apoie-me</strong>.
                </p>
                <p style='color:#555; font-size:15px; line-height:1.7; margin:0 0 24px;'>
                    Confirme a alteração usando o código abaixo ou clicando no botão:
                </p>
                " . _blococodigo($codigo) . "
                " . _botao($link, 'Confirmar novo e-mail') . "
                <p style='color:#999; font-size:13px; margin-top:24px;'>
                    O código é válido por <strong>15 minutos</strong>. Se você não solicitou essa alteração, ignore este e-mail — seu e-mail atual continuará ativo.
                </p>
                "
            );

        case 'deletar_conta':
            return _layout(
                'Confirmação de exclusão de conta',
                "
                <p style='color:#555; font-size:15px; line-height:1.7; margin:0 0 16px;'>
                    Olá, <strong style='color:#2e4a3b;'>$nomeEsc</strong>!<br>
                    Recebemos uma solicitação para <strong style='color:#c0392b;'>excluir permanentemente</strong> sua conta no <strong>Apoie-me</strong>.
                </p>

                <div style='background:#fff5f5; border-left:4px solid #e07b6a; border-radius:0 8px 8px 0; padding:14px 18px; margin:0 0 24px;'>
                    <p style='margin:0; font-size:14px; color:#c0392b; line-height:1.6;'>
                        ⚠️ Esta ação é <strong>irreversível</strong>. Todos os seus dados, serviços e histórico serão apagados e não poderão ser recuperados.
                    </p>
                </div>

                <p style='color:#555; font-size:15px; line-height:1.7; margin:0 0 20px;'>
                    Para confirmar, insira o código abaixo na plataforma:
                </p>
                " . _blococodigo($codigo) . "
                <p style='color:#999; font-size:13px; margin-top:24px;'>
                    O código expira em <strong>15 minutos</strong>. Se você <strong>não</strong> solicitou a exclusão da sua conta, ignore este e-mail — ela permanecerá ativa e nenhuma ação será tomada.
                </p>
                <p style='color:#bbb; font-size:12px; margin-top:12px;'>
                    Se você continuar recebendo e-mails indesejados, entre em contato com o suporte.
                </p>
                "
            );

        default:
            return _layout(
                'Código de verificação',
                "
                <p style='color:#555; font-size:15px; line-height:1.7; margin:0 0 20px;'>
                    Olá, <strong style='color:#2e4a3b;'>$nomeEsc</strong>!<br>
                    Aqui está o seu código de verificação:
                </p>
                " . _blococodigo($codigo) . "
                " . _botao($link, 'Acessar') . "
                <p style='color:#999; font-size:13px; margin-top:24px;'>
                    Se você não esperava este e-mail, ignore-o com segurança.
                </p>
                "
            );
    }
}

function _layout($titulo, $conteudo)
{
    return "
    <!DOCTYPE html>
    <html lang='pt-BR'>
    <head><meta charset='UTF-8'><meta name='viewport' content='width=device-width,initial-scale=1'></head>
    <body style='margin:0; padding:0; background:#f0ede6; font-family:Arial,Helvetica,sans-serif;'>

        <table width='100%' cellpadding='0' cellspacing='0' style='background:#f0ede6; padding:40px 20px;'>
        <tr><td align='center'>

            <table width='600' cellpadding='0' cellspacing='0' style='max-width:600px; width:100%; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.08);'>

                <!-- Cabeçalho -->
                <tr>
                    <td style='background:#2e4a3b; padding:32px 40px; text-align:center;'>
                        <p style='margin:0 0 6px; font-size:13px; color:#b0822b; letter-spacing:2px; text-transform:uppercase;'>Apoie-me Condomínios</p>
                        <h1 style='margin:0; font-size:22px; font-weight:bold; color:#ffffff;'>$titulo</h1>
                    </td>
                </tr>

                <!-- Conteúdo -->
                <tr>
                    <td style='padding:36px 40px;'>
                        $conteudo
                    </td>
                </tr>

                <!-- Rodapé -->
                <tr>
                    <td style='background:#f9f7f2; padding:20px 40px; text-align:center; border-top:1px solid #e8e2d6;'>
                        <p style='margin:0 0 6px; font-size:13px; color:#999;'>
                            Este é um e-mail automático. Por favor, não responda diretamente.
                        </p>
                        <p style='margin:0; font-size:12px; color:#bbb;'>
                            © " . date('Y') . " Apoie-me Condomínios. Todos os direitos reservados.
                        </p>
                    </td>
                </tr>

            </table>

        </td></tr>
        </table>

    </body>
    </html>
    ";
}

function _blococodigo($codigo)
{
    return "
    <div style='text-align:center; background:#fdfaf3; border:1.5px solid #e2d9c5; border-radius:12px; padding:24px 20px; margin:0 0 24px;'>
        <p style='margin:0 0 8px; font-size:13px; color:#888; text-transform:uppercase; letter-spacing:1px;'>Seu código de verificação</p>
        <span style='font-size:40px; font-weight:bold; color:#b0822b; letter-spacing:10px; display:inline-block;'>$codigo</span>
    </div>
    ";
}

function _botao($link, $texto)
{
    $linkEsc = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');
    return "
    <div style='text-align:center; margin:0 0 16px;'>
        <a href='$linkEsc'
           style='display:inline-block; padding:14px 32px; background:#2e4a3b; color:#ffffff;
                  text-decoration:none; border-radius:8px; font-size:15px; font-weight:bold;
                  letter-spacing:.5px;'>
            $texto
        </a>
    </div>
    <p style='text-align:center; font-size:12px; color:#bbb; margin:0 0 8px;'>
        Ou copie e cole o link no seu navegador:<br>
        <a href='$linkEsc' style='color:#b0822b; word-break:break-all;'>$link</a>
    </p>
    ";
}

function altBodyTexto($nome, $codigo, $link, $fluxo, $chave = '')
{
    $base = "Olá, $nome!\n\nSeu código de verificação é: $codigo\n\nAcesse o link: $link\n";
    if ($fluxo === 'chave') {
        $base .= "\nChave do condomínio: $chave\n";
    }
    $base .= "\nEste código é válido por 30 minutos.\n\n— Apoie-me Condomínios";
    return $base;
}

function enviarEmailServico($email, $nome, $nomeServico, $nomeOutro, $dia, $hora, $fluxo)
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

        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ]
        ];

        $mail->setFrom($_ENV['EMAIL_APP'], 'Apoie-me Condomínios');
        $mail->addAddress($email, $nome);
        $mail->isHTML(true);

        $assuntos = [
            'solicitacao_cliente'   => 'Solicitação enviada — Apoie-me',
            'solicitacao_prestador' => 'Nova solicitação de serviço — Apoie-me',
            'confirmacao_cliente'   => 'Serviço confirmado! — Apoie-me',
            'cancelamento_cliente'  => 'Serviço cancelado — Apoie-me',
        ];

        $mail->Subject = $assuntos[$fluxo] ?? 'Atualização de serviço — Apoie-me';
        $mail->Body    = _textoEmailServico($nome, $nomeServico, $nomeOutro, $dia, $hora, $fluxo);
        $mail->AltBody = "Olá, $nome!\n\nServiço: $nomeServico\nData: $dia às $hora\n\n— Apoie-me Condomínios";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function _textoEmailServico($nome, $nomeServico, $nomeOutro, $dia, $hora, $fluxo)
{
    $nomeEsc    = htmlspecialchars($nome);
    $servicoEsc = htmlspecialchars($nomeServico);
    $outroEsc   = htmlspecialchars($nomeOutro);
    $diaFmt     = date('d/m/Y', strtotime($dia));
    $horaFmt    = substr($hora, 0, 5);

    $infoBox = "
    <table style='width:100%; border-collapse:collapse; font-size:14px; margin:20px 0;'>
        <tr>
            <td style='padding:10px 14px; background:#f4f0e8; border-radius:6px 6px 0 0; font-weight:bold; color:#2e4a3b; width:40%;'>Serviço</td>
            <td style='padding:10px 14px; background:#fdfaf3; border-radius:6px 6px 0 0;'>$servicoEsc</td>
        </tr>
        <tr>
            <td style='padding:10px 14px; background:#f4f0e8; font-weight:bold; color:#2e4a3b;'>Data</td>
            <td style='padding:10px 14px; background:#fdfaf3;'>$diaFmt</td>
        </tr>
        <tr>
            <td style='padding:10px 14px; background:#f4f0e8; border-radius:0 0 6px 6px; font-weight:bold; color:#2e4a3b;'>Horário</td>
            <td style='padding:10px 14px; background:#fdfaf3; border-radius:0 0 6px 6px;'>$horaFmt</td>
        </tr>
    </table>";

    switch ($fluxo) {

        case 'solicitacao_cliente':
            return _layout(
                'Solicitação enviada',
                "
                <p style='color:#555; font-size:15px; line-height:1.7; margin:0 0 16px;'>
                    Olá, <strong style='color:#2e4a3b;'>$nomeEsc</strong>!<br>
                    Sua solicitação para o serviço <strong>$servicoEsc</strong> foi enviada ao prestador <strong>$outroEsc</strong>.
                </p>
                <p style='color:#555; font-size:15px; line-height:1.7; margin:0 0 16px;'>
                    Aguarde a confirmação. Você receberá um e-mail assim que o prestador responder.
                </p>
                $infoBox
                <p style='color:#999; font-size:13px; margin-top:24px;'>
                    Caso precise de ajuda, entre em contato pelo site.
                </p>
                "
            );

        case 'solicitacao_prestador':
            return _layout(
                'Nova solicitação de serviço',
                "
                <p style='color:#555; font-size:15px; line-height:1.7; margin:0 0 16px;'>
                    Olá, <strong style='color:#2e4a3b;'>$nomeEsc</strong>!<br>
                    Você recebeu uma nova solicitação do cliente <strong>$outroEsc</strong> para o serviço abaixo.
                </p>
                $infoBox
                <p style='color:#555; font-size:15px; line-height:1.7; margin:0;'>
                    Acesse o painel <strong>Meus Serviços</strong> para aceitar ou cancelar esta solicitação.
                </p>
                "
            );

        case 'confirmacao_cliente':
            return _layout(
                'Serviço confirmado! ✓',
                "
                <p style='color:#555; font-size:15px; line-height:1.7; margin:0 0 16px;'>
                    Olá, <strong style='color:#2e4a3b;'>$nomeEsc</strong>!<br>
                    Boa notícia: o prestador <strong>$outroEsc</strong> confirmou sua solicitação.
                </p>
                $infoBox
                <p style='color:#555; font-size:15px; line-height:1.7; margin:0;'>
                    Lembre-se de estar disponível na data e horário combinados.
                </p>
                "
            );

        case 'cancelamento_cliente':
            return _layout(
                'Serviço cancelado',
                "
                <p style='color:#555; font-size:15px; line-height:1.7; margin:0 0 16px;'>
                    Olá, <strong style='color:#2e4a3b;'>$nomeEsc</strong>!<br>
                    Infelizmente o prestador <strong>$outroEsc</strong> não pôde atender sua solicitação.
                </p>
                $infoBox
                <p style='color:#555; font-size:15px; line-height:1.7; margin:0;'>
                    Você pode buscar outro prestador disponível no site.
                </p>
                "
            );

        default:
            return _layout('Atualização de serviço', "<p>$nomeEsc, houve uma atualização no seu serviço $servicoEsc.</p>");
    }
}
