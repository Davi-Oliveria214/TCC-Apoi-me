<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../util/enviar_email.php');

$acao = $_POST['acao'] ?? '';
$idContrato = $_POST['resp'];
$origem = $_POST['origem'] ?? 'cliente';

// AÇÃO 1 — Cancelar contrato (cliente ou prestador)
if ($acao === 'cancelar') {
    $contrato = request("contratos?id=eq.{$idContrato}");

    if (!$contrato || isset($contrato['error'])) {
        $_SESSION['mensagem'] = 'Não foi possivél achar o contrato do serviço!';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../servicos.php');
        exit;
    }

    $idCliente = $contrato['id_cliente'];
    $nomeServico = $contrato['nome_servico'];
    $nomePrestador = $contrato['nome_prestador'];
    $dia = $contrato['dia'];
    $hora = $contrato['hora'];

    $infoCliente = request("usuario?id=eq.{$idCliente}&select=nome,email");
    $nomeCliente = $infoCliente[0]['nome'];
    $emailCliente = $infoCliente[0]['email'];

    request("contratados?id=eq.$idContrato", 'DELETE');

    if ($origem === 'prestador' && !empty($emailCliente)) {
        enviarEmailServico($emailCliente, $nomeCliente, $nomeServico, $nomePrestador, $dia, $hora, 'pedido_cancelado');
    }

    $_SESSION['mensagem'] = 'Serviço cancelado com sucesso.';
    $_SESSION['tipo'] = 'aviso';

    $destino = ($origem === 'prestador') ? '../anunciar.php' : '../servicos.php';
    header("Location: $destino");
    exit;
}

// AÇÃO 2 — Excluir serviço anunciado + imagem do storage
if ($acao === 'excluir') {

    $id = $_SESSION['id'];
    $idServico = (int) ($_POST['id_servico'] ?? 0);

    if (empty($idServico)) {
        $_SESSION['mensagem'] = 'Serviço inválido.';
        $_SESSION['tipo']     = 'erro';
        header('Location: ../anunciar.php');
        exit;
    }

    $verificar = request("servicos?id_prestador=eq.{$id}&id=eq.{$idServico}", 'GET');

    if (empty($verificar) || isset($verificar['error'])) {
        $_SESSION['mensagem'] = 'Serviço não encontrado ou você não tem permissão para excluí-lo.';
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

    // Exclui do banco
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
