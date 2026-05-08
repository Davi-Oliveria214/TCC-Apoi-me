<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

$pass = $_POST['pass'];
$rptSenha = $_POST['rptSenha'];
$email = $_POST['email'] ?? $_SESSION['email_reset_aprovado'] ?? '';

if (empty($_POST['nome'])) {
    $res = request('usuarios?email=eq.{$email}&select=nome');
}

$nome = $_POST['nome'] ?? $res[0]['nome'];

$msg_pass = "";
$pronto = false;

if (!empty($pass)) {
    if (stripos($pass, $nome) || stripos($pass, $email)) {
        $msg_pass = "A senha não pode conter seu nome ou email";
        $pronto = false;
    } else if (
        !preg_match('/[A-Z]/', $pass) ||
        !preg_match('/[a-z]/', $pass) ||
        !preg_match('/[0-9]/', $pass) ||
        !preg_match('/[\W]/', $pass)
    ) {
        $msg_pass = "A senha precisa ter: letras minúsculas, maiúsculas, número e símbolo";
        $pronto = false;
    } else if ($pass != $rptSenha) {
        $msg_pass = "As senhas não são iguais $rptSenha";
        $pronto = false;
    } else {
        $pronto = true;
    }
}

header('Content-Type: application/json');
$data = ["msg" => $msg_pass, "pronto" => $pronto];
echo json_encode($data);