<?php
if (session_status() ===  PHP_SESSION_NONE) {
    session_start();
}

function exigirLogin()
{
    if (empty($_SESSION['id'])) {
        $_SESSION['mensagem'] = "Você precisa estar logado!";
        header("Location: /index.php");
        exit();
    }
}

function loginFeito()
{
    if (!empty($_SESSION['id'])) {
        $_SESSION['mensagem'] = "Você já está logado!";
        header("Location: /servicos.php");
        exit();
    }
}

function exigirMetodo($metodo = 'POST')
{
    if ($_SERVER['REQUEST_METHOD'] !== $metodo) {
        $_SESSION["mensagem"] = "Acesso negado";
        header("Location: ../index.php");
        exit;
    }
}

function validarCNPJ($cnpj)
{
    $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

    if (strlen($cnpj) != 14) {
        $_SESSION["mensagem"] = "CNPJ inválido.";
        header("Location: ../cadastro.php");
        exit;
    }

    $url = "https://brasilapi.com.br/api/cnpj/v1/" . $cnpj;

    $opts = ["http" => ["ignore_errors" => true]];
    $context = stream_context_create($opts);
    $response = file_get_contents($url, false, $context);

    if (!$response) {
        $_SESSION["mensagem"] = "Erro ao validar CNPJ.";
        header("Location: ../cadastro.php");
        exit;
    }

    $dados = json_decode($response, true);

    if (isset($dados['message'])) {
        $_SESSION["mensagem"] = "CNPJ inválido.";
        header("Location: ../cadastro.php");
        exit;
    }

    $nomeEmpresa = strtoupper($dados['razao_social']);

    if (
        strpos($nomeEmpresa, 'CONDOMINIO') === false &&
        strpos($nomeEmpresa, 'CONDOM') === false
    ) {
        $_SESSION["mensagem"] = "CNPJ não é de condomínio.";
        header("Location: ../cadastro.php");
        exit;
    }

    return $dados;
}

function cadastrar($nome, $email, $senhaHash, $codigo, $img, $tipo_usuario, $dadosCondominio)
{
    $cnpj = null;
    $chave = null;

    if ($tipo_usuario === 'sindico' && $dadosCondominio) {
        $cnpj = $dadosCondominio['cnpj'];

        $resCnpj = request("condominios?cnpj_condominio=eq.{$cnpj}");

        if (!empty($resCnpj) && !isset($resCnpj['error'])) {
            $chave = $resCnpj[0]['codigo'];
        } else {
            do {
                $chave = random_int(1000, 9999);
                $res = request("condominios?codigo=eq.{$chave}");
                $codigoExiste = !empty($res) && !isset($res['error']);
            } while ($codigoExiste);

            $endereco = $dadosCondominio['descricao_tipo_de_logradouro'] . ' ' .
                $dadosCondominio['logradouro'];

            request("condominios", "POST", [
                "nome" => $dadosCondominio['razao_social'],
                "bairro" => $dadosCondominio['bairro'],
                "endereco" => $endereco,
                "municipio" => $dadosCondominio['municipio'],
                "uf" => $dadosCondominio['uf'],
                "cep" => $dadosCondominio['cep'],
                "cnpj_condominio" => $cnpj,
                "codigo" => $chave
            ]);
        }
    }

    $_SESSION['email_verificar'] = $email;

    $enviar = enviarEmail($email, $nome, $codigo, 'cadastro', $chave);

    if (!$enviar) {
        $_SESSION["mensagem"] = "Erro ao enviar email de validação";
        header("Location: ../cadastro.php");
        exit;
    }

    $dados = [
        "nome" => $nome,
        "email" => $email,
        "senha" => $senhaHash,
        "imagem" => $img,
        "codigo_verificacao" => $codigo,
        "email_verificado" => false,
        "codigo_criado_em" => date('Y-m-d H:i:sO'),
        "tipo_usuario" => $tipo_usuario,
        "cnpj_vinculado" => $cnpj
    ];

    $res = request("usuarios", "POST", $dados);

    if (isset($res['error'])) {
        $_SESSION["mensagem"] = "Erro ao cadastrar.";
        header("Location: ../cadastro.php");
        exit;
    }

    header("Location: ../verificar_acesso.php?etapa=aviso&tipo_envio=validar");
    exit;
}