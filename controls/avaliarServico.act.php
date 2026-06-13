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
    $_SESSION['tipo'] = 'aviso';
    header('Location: ../historico.php');
    exit;
}

if (empty($id_servico) || empty($id_cliente) || empty($id_contrato)) {
    $_SESSION['mensagem'] = 'Erro ao enviar avaliação. Dados incompletos.';
    $_SESSION['tipo']     = 'erro';
    header('Location: ../historico.php');
    exit;
}

$avaliou = request(
    "avaliacoes?id_contrato=eq.$id_contrato&id_cliente=eq.$id_cliente&select=id",
    'GET'
);

if (!empty($avaliou) && !isset($avaliou['error'])) {
    $_SESSION['mensagem'] = 'Você já avaliou este serviço.';
    $_SESSION['tipo']     = 'aviso';
    header('Location: ../historico.php');
    exit;
}

$contrato = request(
    "contratados?id=eq.$id_contrato&select=nome_servico,nome_prestador,nome_cliente,hora,dia",
    'GET'
);

$nomeServico  = $contrato[0]['nome_servico']   ?? 'Serviço removido';
$nomePrestador = $contrato[0]['nome_prestador'] ?? 'Prestador removido';
$nomeCliente = $contrato[0]['nome_cliente'] ?? 'Cliente removido';
$hora = $contrato[0]['hora'] ?? '';
$dia = $contrato[0]['dia'] ?? '';

$dadosSalvar = [
    'nota' => $nota,
    'comentario' => !empty($comentario) ? $comentario : 'Nenhum comentário',
    'id_servico' => $id_servico,
    'id_cliente' => $id_cliente,
    'id_contrato' => $id_contrato,
    'nome_servico' => $nomeServico,
    'nome_prestador' => $nomePrestador,
    'nome_cliente' => $nomeCliente,
    'horario' => $hora,
    'data' => $dia
];

$avaliar = request('avaliacoes', 'POST', $dadosSalvar);

if (!$avaliar || isset($avaliar['error'])) {
    $_SESSION['mensagem'] = 'Erro ao enviar avaliação. Tente novamente.';
    $_SESSION['tipo'] = 'erro';
    header('Location: ../historico.php');
    exit;
}

$status = request("contratados?id=eq.$id_contrato", 'PATCH', [
    'avaliar' => true,
]);

if (!$status || isset($status['error'])) {
    $_SESSION['mensagem'] = 'Avaliação enviada, mas houve um problema ao atualizar o status.';
    $_SESSION['tipo'] = 'aviso';
} else {
    $todasNotas = request(
        "avaliacoes?id_servico=eq.$id_servico&select=nota",
        'GET'
    );

    if (!empty($todasNotas) && !isset($todasNotas['error'])) {
        $count = count($todasNotas);
        $soma  = array_sum(array_column($todasNotas, 'nota'));
        $media = ($count > 0) ? round($soma / $count, 1) : 0;

        request("servicos?id=eq.$id_servico", 'PATCH', [
            'nota_geral' => $media,
            'qtd_avaliados' => $count,
        ]);
    }

    $_SESSION['mensagem'] = 'Avaliação enviada com sucesso!';
    $_SESSION['tipo'] = 'sucesso';
}

header('Location: ../historico.php');
exit;
