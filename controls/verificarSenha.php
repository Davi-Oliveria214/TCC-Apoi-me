<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

require_once(__DIR__ . '/../conexao.php');

$pass = $_POST['pass'] ?? '';
$rptSenha = $_POST['rptSenha'] ?? '';
$email = $_POST['email'] ?? $_SESSION['email_reset_aprovado'] ?? '';

$nome = $_POST['nome'] ?? '';
if (empty($nome) && !empty($email)) {
    $res = request("usuarios?email=eq.{$email}&select=nome");
    if (!empty($res) && !isset($res['error'])) {
        $nome = $res[0]['nome'] ?? '';
    }
}

$msg_pass = "";
$pronto = false;

if (!empty($pass)) {
    if (!empty($nome) && (stripos($pass, $nome) !== false || stripos($pass, $email) !== false)) {
        $msg_pass = "A senha não pode conter seu nome ou email";
        $pronto = false;
    } else if (strlen($pass) < 8) {
        $msg_pass = "A senha deve ter no mínimo 8 caracteres";
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
        $msg_pass = "As senhas não são iguais";
        $pronto = false;
    } else {
        $pronto = true;
    }
}

header('Content-Type: application/json');
$data = ["msg" => $msg_pass, "pronto" => $pronto];
echo json_encode($data);