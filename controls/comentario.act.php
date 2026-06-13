<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirLogin();
exigirMetodo('POST');
require_once(__DIR__ . '/../conexao.php');

$acao = $_POST['acao'] ?? '';
$id_comentario = $_POST['id_comentario'] ?? '';
$user_id = $_SESSION['id'];

if (empty($id_comentario)) {
    $_SESSION['mensagem'] = "ID do comentário não informado.";
    $_SESSION['tipo'] = "erro";
    header("Location: ../historico.php");
    exit;
}

// Buscar o comentário para validar permissões
$comentario_data = request("avaliacoes?id=eq.$id_comentario", "GET");
if (empty($comentario_data) || isset($comentario_data['error'])) {
    $_SESSION['mensagem'] = "Comentário não encontrado.";
    $_SESSION['tipo'] = "erro";
    header("Location: ../historico.php");
    exit;
}
$comentario = $comentario_data[0];

if ($acao === 'editar') {
    // Validar se é o autor
    if ($comentario['id_cliente'] != $user_id) {
        $_SESSION['mensagem'] = "Você não tem permissão para editar este comentário.";
        $_SESSION['tipo'] = "erro";
        header("Location: ../historico.php");
        exit;
    }

    $novo_texto = $_POST['comentario'] ?? '';
    $nova_nota = $_POST['nota'] ?? $comentario['nota'];

    if (empty($novo_texto)) {
        $_SESSION['mensagem'] = "O comentário não pode estar vazio.";
        $_SESSION['tipo'] = "erro";
        header("Location: ../historico.php");
        exit;
    }

    $dados = [
        'comentario' => $novo_texto,
        'nota' => $nova_nota,
        'editado_em' => date('Y-m-d H:i:s')
    ];

    $res = request("avaliacoes?id=eq.$id_comentario", "PATCH", $dados);

    if (isset($res['error'])) {
        $_SESSION['mensagem'] = "Erro ao atualizar comentário.";
        $_SESSION['tipo'] = "erro";
    } else {
        // Recalcular média do serviço
        $id_servico = $comentario['id_servico'];
        $avaliacoes = request("avaliacoes?id_servico=eq.$id_servico", "GET");
        if (!empty($avaliacoes) && !isset($avaliacoes['error'])) {
            $soma = 0;
            foreach ($avaliacoes as $av) $soma += $av['nota'];
            $media = $soma / count($avaliacoes);
            request("servicos?id=eq.$id_servico", "PATCH", [
                'nota_geral' => round($media),
                'qtd_avaliados' => count($avaliacoes)
            ]);
        }
        $_SESSION['mensagem'] = "Comentário atualizado com sucesso!";
        $_SESSION['tipo'] = "sucesso";
    }
} 
elseif ($acao === 'excluir') {
    // Validar se é o autor
    if ($comentario['id_cliente'] != $user_id) {
        $_SESSION['mensagem'] = "Você não tem permissão para excluir este comentário.";
        $_SESSION['tipo'] = "erro";
        header("Location: ../historico.php");
        exit;
    }

    $res = request("avaliacoes?id=eq.$id_comentario", "DELETE");

    if (isset($res['error'])) {
        $_SESSION['mensagem'] = "Erro ao excluir comentário.";
        $_SESSION['tipo'] = "erro";
    } else {
        // Voltar o status do contrato para permitir avaliar novamente se desejar? 
        // No PDF diz apenas para excluir. Vamos manter a integridade da nota.
        $id_servico = $comentario['id_servico'];
        $id_contrato = $comentario['id_contrato'];
        
        // Resetar o campo avaliar no contrato
        request("contratados?id=eq.$id_contrato", "PATCH", ['avaliar' => false]);

        // Recalcular média
        $avaliacoes = request("avaliacoes?id_servico=eq.$id_servico", "GET");
        $qtd = 0;
        $media = 0;
        if (!empty($avaliacoes) && !isset($avaliacoes['error'])) {
            $soma = 0;
            foreach ($avaliacoes as $av) $soma += $av['nota'];
            $qtd = count($avaliacoes);
            $media = $soma / $qtd;
        }
        request("servicos?id=eq.$id_servico", "PATCH", [
            'nota_geral' => round($media),
            'qtd_avaliados' => $qtd
        ]);

        $_SESSION['mensagem'] = "Comentário removido com sucesso!";
        $_SESSION['tipo'] = "sucesso";
    }
}
elseif ($acao === 'moderar') {
    // Validar se é o prestador do serviço
    // Buscar o prestador do serviço para validar
    $servico = request("servicos?id=eq." . $comentario['id_servico'], "GET");
    if (empty($servico) || isset($servico['error']) || $servico[0]['id_prestador'] != $user_id) {
        $_SESSION['mensagem'] = "Você não tem permissão para moderar este comentário.";
        $_SESSION['tipo'] = "erro";
        header("Location: ../historico.php");
        exit;
    }

    $motivo = $_POST['motivo'] ?? 'Não especificado';
    
    // Marcar o comentário como inválido em vez de deletar
    $dados = [
        'comentario' => "[Comentário marcado como inválido pelo prestador - Motivo: $motivo]",
        'editado_em' => date('Y-m-d H:i:s')
    ];
    
    $res = request("avaliacoes?id=eq.$id_comentario", "PATCH", $dados);

    if (isset($res['error'])) {
        $_SESSION['mensagem'] = "Erro ao moderar comentário.";
        $_SESSION['tipo'] = "erro";
    } else {
        $_SESSION['mensagem'] = "Comentário marcado como inválido com sucesso!";
        $_SESSION['tipo'] = "sucesso";
    }
}
elseif ($acao === 'desfazer_moderar') {
    // Validar se é o prestador do serviço
    $servico = request("servicos?id=eq." . $comentario['id_servico'], "GET");
    if (empty($servico) || isset($servico['error']) || $servico[0]['id_prestador'] != $user_id) {
        $_SESSION['mensagem'] = "Você não tem permissão para moderar este comentário.";
        $_SESSION['tipo'] = "erro";
        header("Location: ../historico.php");
        exit;
    }

    // Restaurar o comentário (como não temos o backup do texto original no banco, 
    // o prestador só pode "desmarcar", mas o texto continuará como inválido se já foi alterado.
    // Idealmente teríamos uma coluna 'status_moderacao'. 
    // Como solução paliativa para o pedido do usuário, vamos apenas resetar para uma string genérica ou vazia se ele quiser restaurar.
    // Mas o usuário pediu para "apagar" e ficar como "comentário inválido".
    
    // Se o prestador quer desfazer, vamos apenas remover a flag de inválido do texto se possível.
    $novo_texto = str_replace(["[Comentário marcado como inválido pelo prestador - Motivo: ", "]"], "", $comentario['comentario']);
    
    $dados = [
        'comentario' => "Comentário restaurado pelo prestador. (O conteúdo original foi removido durante a moderação)",
        'editado_em' => date('Y-m-d H:i:s')
    ];
    
    $res = request("avaliacoes?id=eq.$id_comentario", "PATCH", $dados);

    if (isset($res['error'])) {
        $_SESSION['mensagem'] = "Erro ao restaurar comentário.";
        $_SESSION['tipo'] = "erro";
    } else {
        $_SESSION['mensagem'] = "Moderação desfeita!";
        $_SESSION['tipo'] = "sucesso";
    }
}

// Redirecionar para a página de origem
$origem = $_SERVER['HTTP_REFERER'] ?? '../historico.php';
header("Location: $origem");
exit;
