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
$preco_servico = (float) $_POST['preco_servico'];
$tipo_cobrado = $_POST['tipo_cobrado'] ?? 'Hora';

$verificar = request("servicos?id_prestador=eq.{$id_prestador}&id=eq.{$id_servico}", "GET");

if (empty($verificar) || isset($verificar['error'])) {
    $_SESSION["mensagem"] = "Erro: Serviço não encontrado ou você não tem permissão.";
    header("Location: ../anunciar.php");
    exit;
}

$data = date("Y/m/d");
if (!empty($_POST['data'])) {
    $data = $_POST['data'];
}

$imagem = $_POST['imagem_selecionada'] ?? $verificar[0]['imagem'];

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
    $bucket = $_ENV['BALDE'];
    $arquivo = $_FILES['imagem'];
    $tmp = $arquivo['tmp_name'];
    $nomeImg = $arquivo['name'];
    $nomeFinal = uniqid() . "_" . $nomeImg;
    $url = trim($_ENV['SUPABASE_URL']) . "/storage/v1/object/$bucket/$nomeFinal";

    $ch = curl_init($url);
    $extensao = pathinfo($nomeImg, PATHINFO_EXTENSION);
    $tipoMime = ($extensao == 'png') ? 'image/png' : 'image/jpeg';

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

    if ($status == 200 || $status == 201) {
        $imagem = $_ENV['SUPABASE_URL'] . "/storage/v1/object/public/$bucket/$nomeFinal";
    }
}

$dadosSalvar = [
    "nome" => $nome,
    "descricao" => $descricao,
    "id_prestador" => $id_prestador,
    "hora_inicio" => $hora_inicio,
    "hora_fim" => $hora_fim,
    "imagem" => $imagem,
    "duracao" => $duracao,
    "preco_servico" => $preco_servico,
    "tipo_cobrado" => $tipo_cobrado
];

$edit = request("servicos?id_prestador=eq.{$id_prestador}&id=eq.{$id_servico}", "PATCH", $dadosSalvar);

if (isset($edit['error'])) {
    $_SESSION["mensagem"] = "Erro ao editar serviço. Tente novamente.";
    $_SESSION["tipo"] = "erro";
    header("Location: ../anunciar.php");
    exit;
}

$_SESSION["mensagem"] = "Serviço editado com sucesso!";
$_SESSION["tipo"] = "sucesso";
header("Location: ../anunciar.php");
exit;