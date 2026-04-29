<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

require_once(__DIR__ . '/../conexao.php');

$campo = $_POST['campo'];
if ($campo === 'criar' || $campo === 'editar') {
    if (empty($_POST['titulo']) || empty($_POST['mensagem']) || empty($_POST['data_evento'])) {
        $_SESSION["mensagem"] = "Preencha os campos obrigatórios.";
        header("Location: ../usuario.php");
        exit;
    }
}

$nomeSindico = request("usuarios?id=eq.{$_SESSION['id']}");

if (isset($nomeSindico['error'])) {
    $_SESSION["mensagem"] = "Erro ao encontrar síndico!";
    header("Location: ../usuario.php");
    exit;
}

$resp = null;
$msgSucesso = "";
$msgErro = "";

if ($campo === 'criar' || $campo === 'editar') {
    $dados = [
        "titulo" => $_POST['titulo'],
        "mensagem" => $_POST['mensagem'],
        "data_evento" => $_POST['data_evento'],
        "id_usuario" => $_SESSION['id'],
        "codigo" => $_SESSION['codigo'],
        "autor" => $nomeSindico[0]['nome']
    ];
}

switch ($campo) {
    case 'criar':
        $resp = request("avisos", "POST", $dados);
        $msgSucesso = "Aviso publicado com sucesso!";
        $msgErro = "Erro ao criar aviso!";
        break;
    case 'editar':
        $resp = request("avisos?id=eq.{$_POST['id_aviso']}", "PATCH", $dados);
        $msgSucesso = "Aviso atualizado com sucesso!";
        $msgErro = "Erro ao atualizar aviso!";
        break;
    case 'apagar':
        $resp = request("avisos?id=eq.{$_POST['id_aviso']}", "DELETE");
        $msgSucesso = "Aviso apagado com sucesso!";
        $msgErro = "Erro ao apagar aviso!";
        break;
    default:
        $_SESSION["mensagem"] = "Erro ao $campo aviso";
        header("Location: ../usuario.php");
        exit;
}

if (isset($resp['error'])) {
    $_SESSION["mensagem"] = $msgErro;
} else {
    $_SESSION["mensagem"] = $msgSucesso;
}

header("Location: ../usuario.php");
exit;
