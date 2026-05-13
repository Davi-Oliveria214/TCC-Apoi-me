<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo('POST');

require_once(__DIR__ . '/../conexao.php');
require_once(__DIR__ . '/../util/enviar_email.php');

if (empty($_POST['campo'])) {
    $_SESSION["mensagem"] = "Ação inválida.";
    header("Location: ../usuario.php");
    exit;
}

$id_usuario = $_SESSION['id'];
$tipo = trim($_POST['campo']);
$valor = $_POST['valor'] ?? '';

if ($tipo !== 'senha' && empty(trim($valor))) {
    $_SESSION["mensagem"] = "Preencha todos os campos.";
    header("Location: ../usuario.php");
    exit;
}

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
            header("Location: ../verificar_acesso.php?etapa=aviso");
            exit;
        }
        break;

    case 'senha':
        $senha_atual = $_POST['senha_atual'] ?? '';
        $nova_senha = $_POST['nova_senha'] ?? '';
        $confirmar_senha = $_POST['confirmar_senha'] ?? '';

        if (empty($senha_atual) || empty($nova_senha) || empty($confirmar_senha)) {
            $_SESSION["mensagem"] = "Preencha todos os campos de senha.";
            header("Location: ../usuario.php");
            exit;
        }

        if ($nova_senha !== $confirmar_senha) {
            $_SESSION["mensagem"] = "A nova senha e a confirmação não coincidem.";
            header("Location: ../usuario.php");
            exit;
        }

        if (strlen($nova_senha) < 6) {
            $_SESSION["mensagem"] = "A nova senha deve ter no mínimo 6 caracteres.";
            header("Location: ../usuario.php");
            exit;
        }

        $user_data = request("usuarios?id=eq.{$id_usuario}&select=senha");

        if (empty($user_data) || isset($user_data['error'])) {
            $_SESSION["mensagem"] = "Erro ao buscar dados do usuário.";
            header("Location: ../usuario.php");
            exit;
        }

        $hash_banco = $user_data[0]['senha'];

        if (!password_verify($senha_atual, $hash_banco)) {
            $_SESSION["mensagem"] = "A senha atual está incorreta.";
            header("Location: ../usuario.php");
            exit;
        }

        $dados = [
            "senha" => password_hash($nova_senha, PASSWORD_DEFAULT)
        ];
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