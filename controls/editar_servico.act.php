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
$preco_servico = (float) str_replace(['R$', ' ', '.', ','], ['', '', '', '.'], $_POST['preco_servico'] ?? 0);
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

$imagem = $_POST['imagem'] ?? $verificar[0]['imagem'];

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
