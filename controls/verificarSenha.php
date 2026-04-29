<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

$pass = $_POST['pass'];
$rptSenha = $_POST['rptSenha'];
$nome = $_POST['nome'];
$email = $_POST['email'];

$msg_pass = "";
$pronto = false;

if (!empty($pass)) {
    if (stripos($pass, $nome) !== false || stripos($pass, $email) !== false) {
        $msg_pass = "Senha não pode conter seu nome ou email";
        $pronto = false;
    } else if (
        !preg_match('/[A-Z]/', $pass) ||
        !preg_match('/[a-z]/', $pass) ||
        !preg_match('/[0-9]/', $pass) ||
        !preg_match('/[\W]/', $pass)
    ) {
        $msg_pass = "Senha precisa ter: maiúscula, minúscula, número e símbolo";
        $pronto = false;
    } else if ($pass != $rptSenha) {
        $msg_pass = "As senhas não são iguais";
        $pronto = false;
    } else {
        $pronto = true;
    }
}

header('Content-Type: application/json');
$data = ["msg" => $msg_pass, "pronto" => $pronto];
echo json_encode($data);