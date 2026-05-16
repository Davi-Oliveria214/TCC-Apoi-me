<?php
require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

$nota       = (int) ($_POST['nota']       ?? 0);
$id_servico = (int) ($_POST['id_servico'] ?? 0);
$id_contrato = (int) ($_POST['id_contrato'] ?? 0);
$id_cliente = (int) ($_SESSION['id']      ?? 0);
$comentario = trim($_POST['comentario']   ?? '');

if ($nota < 1 || $nota > 5) {
    $_SESSION['mensagem'] = 'Escolha ao menos uma estrela para a nota!';
    $_SESSION['tipo']     = 'aviso';
    header('Location: ../historico.php');
    exit;
}

if (empty($id_servico) || empty($id_cliente) || empty($id_contrato)) {
    $_SESSION['mensagem'] = 'Erro ao enviar avaliação. Dados incompletos.';
    $_SESSION['tipo']     = 'erro';
    header('Location: ../historico.php');
    exit;
}

$jaAvaliou = request(
    "avaliacoes?id_contrato=eq.$id_contrato&id_cliente=eq.$id_cliente&select=id",
    'GET'
);

if (!empty($jaAvaliou) && !isset($jaAvaliou['error'])) {
    $_SESSION['mensagem'] = 'Você já avaliou este serviço.';
    $_SESSION['tipo']     = 'aviso';
    header('Location: ../historico.php');
    exit;
}

$contrato = request(
    "contratados?id=eq.$id_contrato&select=nome_servico,nome_prestador",
    'GET'
);

$nomeServico  = $contrato[0]['nome_servico']   ?? 'Serviço removido';
$nomePrestador = $contrato[0]['nome_prestador'] ?? 'Prestador removido';

$dadosSalvar = [
    'nota'           => $nota,
    'comentario'     => !empty($comentario) ? $comentario : 'Nenhum comentário',
    'id_servico'     => $id_servico,
    'id_cliente'     => $id_cliente,
    'id_contrato'    => $id_contrato,
    'nome_servico'   => $nomeServico,
    'nome_prestador' => $nomePrestador
];

$avaliar = request('avaliacoes', 'POST', $dadosSalvar);

if (!$avaliar || isset($avaliar['error'])) {
    $_SESSION['mensagem'] = 'Erro ao enviar avaliação. Tente novamente.';
    $_SESSION['tipo']     = 'erro';
    header('Location: ../historico.php');
    exit;
}

$status = request("contratados?id=eq.$id_contrato", 'PATCH', [
    'avaliar' => true,
]);

if (!$status || isset($status['error'])) {
    $_SESSION['mensagem'] = 'Avaliação enviada, mas houve um problema ao atualizar o status.';
    $_SESSION['tipo']     = 'aviso';
} else {
    $todasNotas = request(
        "avaliacoes?id_servico=eq.$id_servico&select=nota",
        'GET'
    );

    if (!empty($todasNotas) && !isset($todasNotas['error'])) {
        $soma  = array_sum(array_column($todasNotas, 'nota'));
        $media = round($soma / count($todasNotas), 1);

        request("servicos?id=eq.$id_servico", 'PATCH', [
            'nota_geral' => $media,
            'pedidos'    => count($todasNotas),
        ]);
    }

    $_SESSION['mensagem'] = 'Avaliação enviada com sucesso!';
    $_SESSION['tipo']     = 'sucesso';
}

header('Location: ../historico.php');
exit;