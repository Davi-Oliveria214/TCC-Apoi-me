<?php
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

$data;
if (!empty($_POST['data'])) {
    $data = $_POST['data'];
}

$imagem;
if (!empty($_POST['imagem'])) {
    $imagem = $_POST['imagem'];
} else {
    $imagem = "./img/condomino.png";
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