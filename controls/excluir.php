<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

require_once(__DIR__ . '/../conexao.php');

$id = $_SESSION['id'];
$id_servico = $_POST['id_servico'];
$bucket = $_ENV['BALDE'];

$verificar = request("servicos?id_prestador=eq.{$id}&id=eq.{$id_servico}", "GET");

if (empty($verificar) || isset($verificar['error'])) {
    $_SESSION["mensagem"] = "Erro: Serviço não encontrado ou você não tem permissão.";
    header("Location: ../anunciar.php");
    exit;
}

$urlImagemSalva = $verificar[0]['imagem'];

if (!empty($urlImagemSalva)) {
    $nomeFinal = basename(parse_url($urlImagemSalva, PHP_URL_PATH));

    $urlStorage = trim($_ENV['SUPABASE_URL']) . "/storage/v1/object/$bucket/$nomeFinal";

    $ch = curl_init($urlStorage);

    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => "DELETE",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . trim($_ENV['BALDE_KEY'])
        ]
    ]);

    $responseStorage = curl_exec($ch);
    $statusStorage = curl_getinfo($ch, CURLINFO_HTTP_CODE);
}

$del = request("servicos?id_prestador=eq.{$id}&id=eq.{$id_servico}", "DELETE");

if (isset($del['error'])) {
    $_SESSION["mensagem"] = "Não foi possível excluir o serviço do banco.";
} else {
    $_SESSION["mensagem"] = "Serviço e imagem excluídos com sucesso!";
}

header("Location: ../anunciar.php");
exit;