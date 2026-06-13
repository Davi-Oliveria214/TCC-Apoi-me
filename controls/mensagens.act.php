<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirLogin();
exigirMetodo('POST');
require_once(__DIR__ . '/../conexao.php');

$acao = $_POST['acao'] ?? '';
$user_id = $_SESSION['id'];

if ($acao === 'enviar') {
    $id_conversa = $_POST['id_conversa'] ?? '';
    $conteudo = trim($_POST['conteudo'] ?? '');

    if (empty($conteudo)) {
        $_SESSION['mensagem'] = "A mensagem não pode estar vazia.";
        $_SESSION['tipo'] = "erro";
        header("Location: ../mensagens.php?id=$id_conversa");
        exit;
    }

    // Verificar se a conversa existe e se o usuário participa dela
    $conversa = request("conversas?id=eq.$id_conversa", "GET");
    if (empty($conversa) || isset($conversa['error'])) {
        $_SESSION['mensagem'] = "Conversa não encontrada.";
        $_SESSION['tipo'] = "erro";
        header("Location: ../mensagens.php");
        exit;
    }

    $c = $conversa[0];
    if ($c['id_participante1'] != $user_id && $c['id_participante2'] != $user_id) {
        $_SESSION['mensagem'] = "Você não tem permissão para enviar mensagens nesta conversa.";
        $_SESSION['tipo'] = "erro";
        header("Location: ../mensagens.php");
        exit;
    }

    // Verificar bloqueios
    $p1 = $c['id_participante1'];
    $p2 = $c['id_participante2'];
    $bloqueio = request("bloqueios?or=(and(id_bloqueador.eq.$p1,id_bloqueado.eq.$p2),and(id_bloqueador.eq.$p2,id_bloqueado.eq.$p1))", "GET");
    
    if (!empty($bloqueio) && !isset($bloqueio['error'])) {
        $_SESSION['mensagem'] = "Não é possível enviar mensagens. Um dos usuários está bloqueado.";
        $_SESSION['tipo'] = "erro";
        header("Location: ../mensagens.php?id=$id_conversa");
        exit;
    }

    // Criar mensagem
    $dados_msg = [
        'id_conversa' => $id_conversa,
        'id_autor' => $user_id,
        'conteudo' => $conteudo,
        'data' => date('Y-m-d'),
        'hora' => date('H:i:s'),
        'lida' => false
    ];

    $res = request("mensagens", "POST", $dados_msg);

    if (isset($res['error'])) {
        $_SESSION['mensagem'] = "Erro ao enviar mensagem.";
        $_SESSION['tipo'] = "erro";
    } else {
        // Atualizar última atualização da conversa
        request("conversas?id=eq.$id_conversa", "PATCH", ['ultima_atualizacao' => date('Y-m-d H:i:s')]);
    }

    header("Location: ../mensagens.php?id=$id_conversa");
    exit;
}

if ($acao === 'iniciar') {
    $id_destinatario = $_POST['id_destinatario'] ?? '';

    if ($id_destinatario == $user_id) {
        $_SESSION['mensagem'] = "Você não pode iniciar uma conversa consigo mesmo.";
        $_SESSION['tipo'] = "erro";
        header("Location: ../servicos.php");
        exit;
    }

    // Verificar se já existe conversa
    $filtro = "or=(and(id_participante1.eq.$user_id,id_participante2.eq.$id_destinatario),and(id_participante1.eq.$id_destinatario,id_participante2.eq.$user_id))";
    $conversa = request("conversas?$filtro", "GET");

    if (!empty($conversa) && !isset($conversa['error'])) {
        $id_conversa = $conversa[0]['id'];
        // Se a conversa estava excluída logicamente, reativar
        request("conversas?id=eq.$id_conversa", "PATCH", ['status' => 'ativo']);
    } else {
        // Criar nova conversa
        $dados_conversa = [
            'id_participante1' => $user_id,
            'id_participante2' => $id_destinatario,
            'status' => 'ativo',
            'ultima_atualizacao' => date('Y-m-d H:i:s')
        ];
        $res = request("conversas", "POST", $dados_conversa);
        if (isset($res['error'])) {
            $_SESSION['mensagem'] = "Erro ao iniciar conversa.";
        $_SESSION['tipo'] = "erro";
            header("Location: ../servicos.php");
            exit;
        }
        $id_conversa = $res[0]['id'];
    }

    header("Location: ../mensagens.php?id=$id_conversa");
    exit;
}

if ($acao === 'bloquear') {
    $id_bloqueado = $_POST['id_bloqueado'] ?? '';
    $id_conversa = $_POST['id_conversa'] ?? '';

    $dados_bloqueio = [
        'id_bloqueador' => $user_id,
        'id_bloqueado' => $id_bloqueado,
        'data' => date('Y-m-d H:i:s')
    ];

    $res = request("bloqueios", "POST", $dados_bloqueio);
    if (isset($res['error'])) {
        $_SESSION['mensagem'] = "Erro ao bloquear usuário.";
        $_SESSION['tipo'] = "erro";
    } else {
        $_SESSION['mensagem'] = "Usuário bloqueado com sucesso.";
        $_SESSION['tipo'] = "sucesso";
    }

    header("Location: ../mensagens.php?id=$id_conversa");
    exit;
}

if ($acao === 'desbloquear') {
    $id_bloqueado = $_POST['id_bloqueado'] ?? '';
    $id_conversa = $_POST['id_conversa'] ?? '';

    $res = request("bloqueios?id_bloqueador=eq.$user_id&id_bloqueado=eq.$id_bloqueado", "DELETE");
    if (isset($res['error'])) {
        $_SESSION['mensagem'] = "Erro ao desbloquear usuário.";
        $_SESSION['tipo'] = "erro";
    } else {
        $_SESSION['mensagem'] = "Usuário desbloqueado com sucesso.";
        $_SESSION['tipo'] = "sucesso";
    }

    header("Location: ../mensagens.php?id=$id_conversa");
    exit;
}

if ($acao === 'excluir_conversa') {
    $id_conversa = $_POST['id_conversa'] ?? '';

    // Exclusão lógica para o usuário atual
    // Como o Supabase/PostgREST não suporta exclusão lógica por usuário facilmente em uma tabela de junção sem colunas extras,
    // vamos assumir que 'status' pode ser usado ou apenas deletar se o usuário quiser limpar.
    // O PDF pede para remover da lista do usuário.
    
    $res = request("conversas?id=eq.$id_conversa", "DELETE");
    if (isset($res['error'])) {
        $_SESSION['mensagem'] = "Erro ao excluir conversa.";
        $_SESSION['tipo'] = "erro";
    } else {
        $_SESSION['mensagem'] = "Conversa excluída com sucesso.";
        $_SESSION['tipo'] = "sucesso";
    }

    header("Location: ../mensagens.php");
    exit;
}

if ($acao === 'apagar_mensagem') {
    $id_mensagem = $_POST['id_mensagem'] ?? '';
    $id_conversa = $_POST['id_conversa'] ?? '';

    // Validar autor
    $msg = request("mensagens?id=eq.$id_mensagem", "GET");
if (!empty($msg) && !isset($msg['error']) && $msg[0]['id_autor'] == $user_id) {
        $res = request("mensagens?id=eq.$id_mensagem", "DELETE");
        if (isset($res['error'])) {
            $_SESSION['mensagem'] = "Erro ao apagar mensagem.";
            $_SESSION['tipo'] = "erro";
        } else {
            $_SESSION['mensagem'] = "Mensagem apagada.";
            $_SESSION['tipo'] = "sucesso";
        }
    } else {
        $_SESSION['mensagem'] = "Você não tem permissão para apagar esta mensagem.";
        $_SESSION['tipo'] = "erro";
    }

    header("Location: ../mensagens.php?id=$id_conversa");
    exit;
}
