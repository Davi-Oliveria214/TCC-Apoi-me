<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

require_once(__DIR__ . '/../conexao.php');

$camposObrigatorios = ['nome', 'categoria', 'descricao', 'hora_inicio', 'hora_fim', 'duracao'];

foreach ($camposObrigatorios as $campo) {
    if (!isset($_POST[$campo]) || trim($_POST[$campo]) === '') {
        $_SESSION["mensagem"] = "Preencha todos os campos obrigatórios.";
        header("Location: ../anunciar.php");
        exit;
    }
}

$id = $_SESSION['id'];
$codigo = $_SESSION['condominio_id'];
$nome = $_POST['nome'];
$categoria = $_POST['categoria'];
$descricao = $_POST['descricao'];
$hora_inicio = $_POST['hora_inicio'];
$hora_fim = $_POST['hora_fim'];
$duracao = $_POST['duracao'];

$bucket = $_ENV['BALDE'];
$imagem = trim($_ENV['SUPABASE_URL']) . "/storage/v1/object/$bucket/deufalt.png";

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
    $arquivo = $_FILES['imagem'];

    $tmp = $arquivo['tmp_name'];
    $nomeImg = $arquivo['name'];

    $nomeFinal = uniqid() . "_" . $nomeImg;

    $url = trim($_ENV['SUPABASE_URL']) . "/storage/v1/object/$bucket/$nomeFinal";

    $ch = curl_init($url);

    $extensao = pathinfo($nomeImg, PATHINFO_EXTENSION);
    $tipoMime = ($extensao == 'png') ? 'image/png' : 'image/jpeg';

    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . trim($_ENV['BALDE_KEY']),
            "Content-Type: " . $tipoMime
        ],
        CURLOPT_POSTFIELDS => file_get_contents($tmp)
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $_SESSION["mensagem"] = "Erro ao enviar a imagem. Tente novamente com um arquivo menor.";
        $_SESSION["tipo"] = "erro";
        header("Location: ../anunciar.php");
        exit;
    }

    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($status != 200 && $status != 201) {
        $_SESSION["mensagem"] = "Erro ao enviar a imagem. Verifique o formato e tente novamente.";
        $_SESSION["tipo"] = "erro";
        header("Location: ../anunciar.php");
        exit;
    }

    $imagem = $_ENV['SUPABASE_URL'] . "/storage/v1/object/public/$bucket/$nomeFinal";
}

$dadosSalvar = [
    "nome" => $nome,
    "descricao" => $descricao,
    "imagem" => $imagem,
    "id_prestador" => $id,
    "categoria" => $categoria,
    "codigo" => $codigo,
    "hora_inicio" => $hora_inicio,
    "hora_fim" => $hora_fim,
    "duracao" => $duracao,
    "preco_servico" => (float) str_replace(['R$', ' ', '.', ','], ['', '', '', '.'], $_POST['preco_servico'] ?? 0),
    "tipo_cobrado" => $_POST['tipo_cobrado'] ?? 'Hora'
];

$sql = request("servicos", "POST", $dadosSalvar);

if (isset($sql['error'])) {
    $_SESSION["mensagem"] = "Erro ao anunciar serviço. Verifique os dados e tente novamente.";
    $_SESSION["tipo"] = "erro";
    header("Location: ../anunciar.php");
    exit;
}

$_SESSION["mensagem"] = "Serviço anunciado com sucesso!";
$_SESSION["tipo"] = "sucesso";

header("Location: ../anunciar.php");
exit;