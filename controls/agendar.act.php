<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();
exigirLogin();

require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../util/enviar_email.php');

if (empty($_POST['id_servico']) || empty($_POST['data']) || empty($_POST['hora']) || empty($_POST['meio_pagamento'])) {
    $_SESSION["mensagem"] = "Preencha todos os campos obrigatórios.";
    header("Location: ../servicos.php");
    exit();
}

$idServico = $_POST['id_servico'];
$d = $_POST['data'];
$h = $_POST['hora'];
$obs = $_POST['observacao'] ?? '';
$meio_pagamento = $_POST['meio_pagamento'];
$idCliente = $_SESSION['id'];

$dadosServico = request("servicos?select=id_prestador,nome,preco_servico&id=eq.{$idServico}", "GET");
$idPrestador  = $dadosServico[0]['id_prestador'];

if ($idPrestador == $idCliente) {
    $_SESSION["mensagem"] = "Você não pode contratar o próprio serviço!";
    header("Location: ../servicos.php");
    exit();
}

$existe = request("contratados?dia=eq.$d&hora=eq.$h&id_servico=eq.{$idServico}");

if (!empty($existe) && !isset($existe['error'])) {
    $_SESSION["mensagem"] = "Esse horário já foi reservado!";
    header("Location: ../servicos.php");
    exit();
}

$user= request("usuarios?id=eq.{$idCliente}&select=nome,email");
$prestador = request("usuarios?id=eq.{$idPrestador}&select=nome,email");

$dadosParaSalvar = [
    "hora" => $h,
    "dia" => $d,
    "id_servico" => $idServico,
    "id_prestador" => $idPrestador,
    "id_cliente" => $idCliente,
    "observacao" => $obs,
    "nome_servico" => $dadosServico[0]['nome'],
    "nome_cliente" => $user[0]['nome'],
    "nome_prestador" => $prestador[0]['nome'],
    "preco_contrato" => $dadosServico[0]['preco_servico'],
    "confirmado" => "pendente",
    "meio_pagamento" => strtolower($meio_pagamento)
];

$sql = request("contratados", "POST", $dadosParaSalvar);

if (!$sql || isset($sql['error'])) {
    $_SESSION["mensagem"] = "Erro ao agendar o serviço. Tente novamente.";
    $_SESSION["tipo"]     = "erro";
} else {
    enviarEmailServico(
        $user[0]['email'],
        $user[0]['nome'],
        $dadosServico[0]['nome'],
        $prestador[0]['nome'],
        $d,
        $h,
        'solicitacao_cliente'
    );

    enviarEmailServico(
        $prestador[0]['email'],
        $prestador[0]['nome'],
        $dadosServico[0]['nome'],
        $user[0]['nome'],
        $d,
        $h,
        'solicitacao_prestador'
    );

    $_SESSION["mensagem"] = "Serviço agendado com sucesso! Aguarde a confirmação do prestador.";
}

header("Location: ../servicos.php");
exit();