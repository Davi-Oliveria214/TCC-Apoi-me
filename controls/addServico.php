<?php
session_start();
require_once(__DIR__ . '/../conexao.php');

if (empty($_POST['nome']) || empty($_POST['categorias']) || empty($_POST['descricao'])) {
    $_SESSION["mensagem"] = "Preencha os campos obrigatórios.";
    header("Location: ../addServico.php");
    exit;
}

$id = $_SESSION['id'];
$codigo = $_SESSION['codigo'];
$nome = $_POST['nome'];
$categoria = $_POST['categorias'];
$descricao = $_POST['descricao'];

if (!empty($_POST['data'])) {
    $data = $_POST['data'];
}

if (!empty($_POST['horario'])) {
    $horario = $_POST['horario'];
}

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
    "imagen" => $imagem
];

$sql = request("servicos", "POST", $dados);

if (isset($sql['error'])) {
    $_SESSION["mensagem"] = "Erro ao enviar serviço";
    header("Location: ../addServico.php.php");
    exit;
}

$_SESSION["mensagem"] = "Serviço anúnciado com sucesso!!!";
header("Location: ../addServico.php.php");
exit;