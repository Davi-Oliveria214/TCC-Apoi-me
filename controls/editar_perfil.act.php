<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../util/enviar_email.php');

if (empty($_POST['valor']) || empty($_POST['campo'])) {
    $_SESSION["mensagem"] = "Preencha todos os campos.";
    header("Location: ../usuario.php");
    exit;
}

$id_usuario = $_SESSION['id'];
$tipo = trim($_POST['campo']);
$valor = trim($_POST['valor']);

$dados = null;
switch ($tipo) {
    case 'nome':
        $dados = [
            "nome" => $valor
        ];
        break;
    case 'email':
        if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["mensagem"] = "E-mail inválido!";
            header("Location: ../usuario.php");
            exit;
        }
        $email = request("usuarios?id=eq.{$id_usuario}&select=email");

        $codigo = random_int(100000, 999999);
        $enviado = enviarEmail($email[0]['email'], '', $codigo, 'alterar_email', '', $valor);

        if (!$enviado) {
            $_SESSION["mensagem"] = "Erro ao enviar código de verificação para o novo email";
            header("Location: ../usuario.php");
            exit;
        } else {
            $agora = date('Y-m-d H:i:sO');
            $dados = [
                "codigo_verificacao" => $codigo,
                "codigo_criado_em" => $agora
            ];
            request("usuarios?id=eq.{$id_usuario}", "PATCH", $dados);
            header("Location: ../aviso_codigo.php");
            exit;
        }
        break;
    case 'senha':
        break;
    case 'codigo':
        $verificar = request("condominios?codigo=eq.{$valor}");

        if (empty($verificar) || isset($verificar['error'])) {
            $_SESSION["mensagem"] = "Codigo de condominio inválido!";
            header("Location: ../usuario.php");
            exit;
        }

        $dados = [
            "codigo" => $valor
        ];
        break;
    default:
        $_SESSION["mensagem"] = "Erro ao editar perfil!";
        header("Location: ../usuario.php");
        exit;
}

$sql = request("usuarios?id=eq.{$id_usuario}", "PATCH", $dados);

if (isset($sql['error'])) {
    $_SESSION["mensagem"] = "Erro ao atualizar $tipo";
} else {
    $_SESSION["mensagem"] = "$tipo atualizado com sucesso!!";
}

header("Location: ../usuario.php");
exit;