<?php
session_start();
require_once(__DIR__ . '/../conexao.php');

$contato = $_POST['contato'];
$id_logado = $_SESSION['id'];

$usuario = request("usuarios?email=eq.$contato&select=id");

if (empty($usuario) || isset($usuario['error'])) {
    $_SESSION["mensagem"] = "Usuário não encontrado.";
    header("Location: ../mensagens.php");
    exit;
}

$id_usuario = $usuario[0]['id'];

if ($id_logado == $id_usuario) {
    $_SESSION["mensagem"] = "Você não pode iniciar um chat você mesmo.";
    header("Location: ../mensagens.php");
    exit;
}

$filtro = "or=(and(id_usuario1.eq.{$id_logado},id_usuario2.eq.{$id_usuario}),and(id_usuario1.eq.{$id_usuario},id_usuario2.eq.{$id_logado}))";

$conversa = request("conversas?{$filtro}", "GET");

if (!empty($conversa) && !isset($conversa['error'])) {
    $id_conversa = $conversa[0]['id'];
    header("Location: ../mensagens.php?id_conversa=$id_conversa");
    exit;
}

$dados = [
    "id_usuario1" => $id_logado,
    "id_usuario2" => $id_usuario
];

$novaconversa = request("conversas", "POST", $dados);

if (isset($novaconversa[0]['id'])) {
    header("Location: ../mensagens.php?id_conversa=" . $novaconversa[0]['id']);
} else {
    header("Location: ../mensagens.php");
}

exit;