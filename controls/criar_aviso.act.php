<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

require_once(__DIR__ . '/../conexao.php');

if (empty($_POST['titulo']) || empty($_POST['mensagem']) || empty($_POST['data_evento'])) {
    $_SESSION["mensagem"] = "Preencha os campos obrigatórios.";
    header("Location: ../usuario.php");
    exit;
}

$nomeSindico = request("usuarios?id=eq.{$_SESSION['id']}");

$dados = [
    "titulo" => $_POST['titulo'],
    "mensagem" => $_POST['mensagem'],
    "data_evento" => $_POST['data_evento'],
    "id_usuario" => $_SESSION['id'],
    "codigo" => $_SESSION['codigo'],
    "autor" => $nomeSindico[0]['nome']
];

$resp = request("avisos", "POST", $dados);

if (isset($resp['error'])) {
    $_SESSION["mensagem"] = "Erro ao adicionar aviso!";
} else {
    $_SESSION["mensagem"] = "Aviso feito com sucesso!";
}

header("Location: ../usuario.php");
exit;