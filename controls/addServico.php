<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once(__DIR__ . '/../conexao.php');

if (empty($_POST['nome']) || empty($_POST['categoria']) || empty($_POST['descricao']) || empty($_POST['hora_inicio']) || empty($_POST['hora_fim']) || empty($_POST['duracao'])) {
    $_SESSION["mensagem"] = "Preencha os campos obrigatórios.";
    header("Location: ../anunciar.php");
    exit;
}

$id = $_SESSION['id'];
$codigo = $_SESSION['codigo'];
$nome = $_POST['nome'];
$categoria = $_POST['categoria'];
$descricao = $_POST['descricao'];
$hora_inicio = $_POST['hora_inicio'];
$hora_fim = $_POST['hora_fim'];
$duracao = $_POST['duracao'];

$data = !empty($_POST['data']) ? $_POST['data'] : null;

$bucket = $_ENV['BALDE'];
$imagem = trim($_ENV['SUPABASE_URL']) . "/storage/v1/object/$bucket/deufalt.png";

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
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

    if (curl_errno($ch)) {
        $_SESSION["mensagem"] = "Erro CURL: " . curl_error($ch);
        header("Location: ../anunciar.php");
        exit;
    }

    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($status != 200 && $status != 201) {
        $_SESSION["mensagem"] = "Erro no upload (HTTP $status): $response";
        header("Location: ../anunciar.php");
        exit;
    }

    $imagem = $_ENV['SUPABASE_URL'] . "/storage/v1/object/public/$bucket/$nomeFinal";
}

$dadosSalvar = [
    "nome" => $nome,
    "descricao" => $descricao,
    "codigo" => $codigo,
    "categoria" => $categoria,
    "id_prestador" => $id,
    "hora_inicio" => $hora_inicio,
    "hora_fim" => $hora_fim,
    "duracao" => $duracao,
    "dia" => $data,
    "imagem" => $imagem
];

$sql = request("servicos", "POST", $dadosSalvar);

if (isset($sql['error'])) {
    $_SESSION["mensagem"] = "Erro ao enviar serviço";
    header("Location: ../anunciar.php");
    exit;
}

$_SESSION["mensagem"] = "Serviço anúnciado com sucesso!!!";
header("Location: ../anunciar.php");
exit;
