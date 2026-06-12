<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

require_once(__DIR__ . '/../conexao.php');

if (empty($_POST['nome']) || empty($_POST['descricao']) || empty($_POST['hora_inicio']) || empty($_POST['hora_fim']) || empty($_POST['duracao'])) {
    $_SESSION["mensagem"] = "Preencha os campos obrigatórios.";
    header("Location: ../anunciar.php");
    exit;
}

$id_prestador = $_SESSION['id'];
$id_servico = $_POST['id_servico'];
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$hora_inicio = $_POST['hora_inicio'];
$hora_fim = $_POST['hora_fim'];
$duracao = $_POST['duracao'];

$verificar = request("servicos?id_prestador=eq.{$id_prestador}&id=eq.{$id_servico}", "GET");

if (empty($verificar) || isset($verificar['error'])) {
    $_SESSION["mensagem"] = "Erro: Serviço não encontrado ou você não tem permissão.";
    header("Location: ../anunciar.php");
    exit;
}

$bucket = $_ENV['BALDE'];
$imagem = $verificar[0]['imagem'];

// Verificar se ha upload de nova imagem
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
    $arquivo = $_FILES['imagem'];
    $tmp = $arquivo['tmp_name'];
    $nomeImg = $arquivo['name'];
    $tamanho = $arquivo['size'];

    $extensao = strtolower(pathinfo($nomeImg, PATHINFO_EXTENSION));
    $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!in_array($extensao, $extensoesPermitidas)) {
        $_SESSION["mensagem"] = "Tipo de arquivo nao permitido. Use: JPG, PNG, GIF ou WEBP.";
        header("Location: ../anunciar.php");
        exit;
    }

    // Validar tamanho (maximo 5MB)
    $tamanhoMaximo = 5 * 1024 * 1024;
    if ($tamanho > $tamanhoMaximo) {
        $_SESSION["mensagem"] = "Arquivo muito grande. Maximo 5MB.";
        header("Location: ../anunciar.php");
        exit;
    }

    $nomeFinal = uniqid() . "_" . $nomeImg;
    $url = trim($_ENV['SUPABASE_URL']) . "/storage/v1/object/$bucket/$nomeFinal";
    $ch = curl_init($url);

    $tipoMime = ($extensao == 'png') ? 'image/png' : ($extensao == 'gif' ? 'image/gif' : ($extensao == 'webp' ? 'image/webp' : 'image/jpeg'));

    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . trim($_ENV['BALDE_KEY']),
            "Content-Type: " . $tipoMime
        ],
        CURLOPT_POSTFIELDS => file_get_contents($tmp)
    ]);

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($status != 200 && $status != 201) {
        $_SESSION["mensagem"] = "Erro no upload de imagem (HTTP $status).";
        header("Location: ../anunciar.php");
        exit;
    }

    $imagem = $_ENV['SUPABASE_URL'] . "/storage/v1/object/public/$bucket/$nomeFinal";
}

$dadosSalvar = [
    "nome" => $nome,
    "descricao" => $descricao,
    "id_prestador" => $id_prestador,
    "hora_inicio" => $hora_inicio,
    "hora_fim" => $hora_fim,
    "imagem" => $imagem,
    "duracao" => $duracao,
    "preco_servico" => $_POST['preco_servico'] ?? $verificar[0]['preco_servico'],
    "tipo_cobrado" => strtolower($_POST['tipo_cobrado'] ?? $verificar[0]['tipo_cobrado'])
];

$edit = request("servicos?id_prestador=eq.{$id_prestador}&id=eq.{$id_servico}", "PATCH", $dadosSalvar);

if (isset($edit['error'])) {
    $_SESSION["mensagem"] = "Erro ao editar serviço";
    header("Location: ../anunciar.php");
    exit;
}

$_SESSION["mensagem"] = "Serviço editado com sucesso!!!";
header("Location: ../anunciar.php");
exit;