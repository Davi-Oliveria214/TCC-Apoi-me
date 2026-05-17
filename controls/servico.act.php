<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();
exigirLogin();

require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../util/enviar_email.php');

$acao = $_POST['acao'] ?? '';

/* ══════════════════════════════════════════════════════════════════
   AÇÃO 1 — Prestador aceita ou recusa um contrato pendente
══════════════════════════════════════════════════════════════════ */
if ($acao === 'aceitar' || $acao === 'recusar') {

    $idContrato  = $_POST['id_contrato'] ?? '';
    $idPrestador = $_SESSION['id'];

    if (empty($idContrato)) {
        $_SESSION['mensagem'] = 'Dados inválidos.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../anunciar.php');
        exit;
    }

    // Busca o contrato garantindo que pertence a este prestador
    $contrato = request("contratados?id=eq.{$idContrato}&id_prestador=eq.{$idPrestador}&select=*");

    if (empty($contrato) || isset($contrato['error'])) {
        $_SESSION['mensagem'] = 'Contrato não encontrado.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../anunciar.php');
        exit;
    }

    $c = $contrato[0];

    // Busca dados do cliente para enviar e-mail
    $cliente = request("usuarios?id=eq.{$c['id_cliente']}&select=nome,email");

    if (empty($cliente) || isset($cliente['error'])) {
        $_SESSION['mensagem'] = 'Cliente não encontrado.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../anunciar.php');
        exit;
    }

    $novoStatus = ($acao === 'aceitar') ? 'confirmado' : 'cancelado';

    // Atualiza o campo "confirmado" no banco
    $atualizar = request(
        "contratados?id=eq.{$idContrato}",
        'PATCH',
        ['confirmado' => $novoStatus]
    );

    if (isset($atualizar['error'])) {
        $_SESSION['mensagem'] = 'Erro ao atualizar o contrato. Tente novamente.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../anunciar.php');
        exit;
    }

    // Notifica o cliente por e-mail
    $fluxoEmail = ($acao === 'aceitar') ? 'confirmacao_cliente' : 'cancelamento_cliente';

    enviarEmailServico(
        $cliente[0]['email'],
        $cliente[0]['nome'],
        $c['nome_servico'],
        $c['nome_prestador'],
        $c['dia'],
        $c['hora'],
        $fluxoEmail
    );

    $_SESSION['mensagem'] = ($acao === 'aceitar')
        ? 'Serviço confirmado! O cliente foi notificado por e-mail.'
        : 'Solicitação recusada. O cliente foi notificado por e-mail.';
    $_SESSION['tipo'] = ($acao === 'aceitar') ? 'sucesso' : 'aviso';

    header('Location: ../anunciar.php');
    exit;
}

/* ══════════════════════════════════════════════════════════════════
   AÇÃO 2 — Cliente ou prestador cancela um contrato já existente
══════════════════════════════════════════════════════════════════ */
if ($acao === 'cancelar') {

    $idContrato = $_POST['resp'] ?? '';
    $origem     = $_POST['origem'] ?? 'cliente';

    if (empty($idContrato)) {
        $_SESSION['mensagem'] = 'Dados inválidos.';
        $_SESSION['tipo']     = 'erro';
        $destino = ($origem === 'prestador') ? '../anunciar.php' : '../servicos.php';
        header("Location: $destino");
        exit;
    }

    $contrato = request("contratados?id=eq.{$idContrato}&select=*");

    if (empty($contrato) || isset($contrato['error'])) {
        $_SESSION['mensagem'] = 'Contrato não encontrado.';
        $_SESSION['tipo']     = 'erro';
        $destino = ($origem === 'prestador') ? '../anunciar.php' : '../servicos.php';
        header("Location: $destino");
        exit;
    }

    $c = $contrato[0];

    // Remove o contrato do banco
    request("contratados?id=eq.{$idContrato}", 'DELETE');

    // Notifica a outra parte por e-mail
    if ($origem === 'prestador') {
        // Prestador cancelou → avisa o cliente
        $cliente = request("usuarios?id=eq.{$c['id_cliente']}&select=nome,email");
        if (!empty($cliente) && !isset($cliente['error'])) {
            enviarEmailServico(
                $cliente[0]['email'],
                $cliente[0]['nome'],
                $c['nome_servico'],
                $c['nome_prestador'],
                $c['dia'],
                $c['hora'],
                'pedido_cancelado'
            );
        }
    } else {
        // Cliente cancelou -> avisa o prestador
        $prestador = request("usuarios?id=eq.{$c['id_prestador']}&select=nome,email");
        if (!empty($prestador) && !isset($prestador['error'])) {
            enviarEmailServico(
                $prestador[0]['email'],
                $prestador[0]['nome'],
                $c['nome_servico'],
                $c['nome_cliente'],
                $c['dia'],
                $c['hora'],
                'pedido_cancelado'
            );
        }
    }

    $_SESSION['mensagem'] = 'Serviço cancelado com sucesso.';
    $_SESSION['tipo']     = 'aviso';

    $destino = ($origem === 'prestador') ? '../anunciar.php' : '../servicos.php';
    header("Location: $destino");
    exit;
}

/* ══════════════════════════════════════════════════════════════════
   AÇÃO 3 — Prestador exclui um serviço anunciado
══════════════════════════════════════════════════════════════════ */
if ($acao === 'excluir') {

    $id        = $_SESSION['id'];
    $idServico = (int) ($_POST['id_servico'] ?? 0);

    if (empty($idServico)) {
        $_SESSION['mensagem'] = 'Serviço inválido.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../anunciar.php');
        exit;
    }

    $verificar = request("servicos?id_prestador=eq.{$id}&id=eq.{$idServico}", 'GET');

    if (empty($verificar) || isset($verificar['error'])) {
        $_SESSION['mensagem'] = 'Serviço não encontrado ou sem permissão para excluí-lo.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../anunciar.php');
        exit;
    }

    $contratado = request("contratados?id_servico=eq.{$idServico}&select=count");
    $qtd = (int) ($contratado[0]['count'] ?? 0);

    if ($qtd > 0) {
        $_SESSION['mensagem'] = 'Este serviço possui contratações ativas. Cancele ou finalize-as antes de excluir.';
        $_SESSION['tipo']     = 'aviso';
        header('Location: ../anunciar.php');
        exit;
    }

    $urlImagem = $verificar[0]['imagem'] ?? '';
    $bucket    = $_ENV['BALDE'];
    $imgPadrao = trim($_ENV['SUPABASE_URL']) . "/storage/v1/object/$bucket/deufalt.png";

    if (!empty($urlImagem) && $urlImagem !== $imgPadrao) {
        $nomeFinal  = basename(parse_url($urlImagem, PHP_URL_PATH));
        $urlStorage = trim($_ENV['SUPABASE_URL']) . "/storage/v1/object/$bucket/$nomeFinal";

        $ch = curl_init($urlStorage);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => 'DELETE',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . trim($_ENV['BALDE_KEY']),
            ],
        ]);
        curl_exec($ch);
    }

    $del = request("servicos?id_prestador=eq.{$id}&id=eq.{$idServico}", 'DELETE');

    if (isset($del['error'])) {
        $_SESSION['mensagem'] = 'Não foi possível excluir o serviço. Tente novamente.';
        $_SESSION['tipo']     = 'erro';
    } else {
        $_SESSION['mensagem'] = 'Serviço excluído com sucesso!';
        $_SESSION['tipo']     = 'sucesso';
    }

    header('Location: ../anunciar.php');
    exit;
}

$_SESSION['mensagem'] = 'Ação inválida.';
$_SESSION['tipo']     = 'erro';
header('Location: ../anunciar.php');
exit;