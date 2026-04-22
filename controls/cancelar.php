<?php
session_start();
require_once(__DIR__ . '/../conexao.php');

if (!empty($_POST['resp'])) {
    $id = $_POST['resp'];
    $res = request("contratados?id=eq.$id", "DELETE");

    $_SESSION['mensagem'] = "Serviço cancelado com sucesso!";
    header("Location: ../servicos.php");
    exit();
} else {
    $_SESSION['mensagem'] = "Erro ao cancelar o serviço.";
    header("Location: ../servicos.php");
    exit();
}