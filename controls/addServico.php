<?php
session_start();
require_once(__DIR__ . '/../conexao.php');

if (empty($_POST['nome']) || empty($_POST['categoria']) || empty($_POST['descricao'])) {
    $_SESSION["mensagem"] = "Preencha os campos obrigatórios.";
    header("Location: ../anunciar.php");
    exit;
}

$id = $_SESSION['id'];
$codigo = $_SESSION['codigo'];
$nome = $_POST['nome'];
$categoria = $_POST['categoria'];
$descricao = $_POST['descricao'];

$data;
if (!empty($_POST['data'])) {
    $data = $_POST['data'];
}

$horario;
if (!empty($_POST['horario'])) {
    $horario = $_POST['horario'];
}

$imagem;
if (!empty($_POST['imagem'])) {
    $imagem = $_POST['imagem'];
} else {
    $imagem = "./img/condominio.png";
}

$dados = [
    "nome" => $nome,
    "descricao" => $descricao,
    "codigo" => $codigo,
    "categoria" => $categoria,
    "id_prestador" => $id,
    "hora_inicio" => $horario,
    "dia" => $data,
    "imagem" => $imagem
];

$sql = request("servicos", "POST", $dados);

if (isset($sql['error'])) {
    $_SESSION["mensagem"] = "Erro ao enviar serviço";
    header("Location: ../anunciar.php");
    exit;
}

$_SESSION["mensagem"] = "Serviço anúnciado com sucesso!!!";
header("Location: ../anunciar.php");
exit;