<?php
session_start();
require_once(__DIR__ . '/../../conexao.php'); 

$tipo_busca = $_POST['enviar-pedido'];
$contato = $_POST['contato'];
$id_logado = $_SESSION['id'];

$usuario = request("usuarios?{$tipo_busca}=eq.{$contato}&select=id");

if (empty($usuario) || isset($usuario['error'])) {
    $_SESSION["mensagem"] = "Usuário não encontrado.";
    header("Location: ../../mensagens.php");
    exit;
}

$id_usuario = $usuario[0]['id'];

$dados = [
    "id_usuario1" => $id_logado,
    "id_usuario2" => $id_usuario
];

$novaconversa = request("conversas", "POST", $dados);

header("Location: ../../mensagens.php");
exit;