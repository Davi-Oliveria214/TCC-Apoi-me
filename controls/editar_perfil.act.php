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

if ($tipo !== 'senha' && $tipo !== 'imagem_perfil' && empty(trim($valor))) {
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

        $userAtual = request("usuarios?id=eq.{$id_usuario}&select=nome,email");

        if (empty($userAtual) || isset($userAtual['error'])) {
            $_SESSION["mensagem"] = "Erro ao buscar dados do usuário.";
            header("Location: ../usuario.php");
            exit;
        }

        $emailAtual = $userAtual[0]['email'];
        $nomeAtual  = $userAtual[0]['nome'];

        $codigo  = random_int(100000, 999999);
        $enviado = enviarEmail($emailAtual, $nomeAtual, $codigo, 'alterar_email', '', $valor);

        if (!$enviado) {
            $_SESSION["mensagem"] = "Erro ao enviar código de verificação. Tente novamente.";
            header("Location: ../usuario.php");
            exit;
        }

        $agora = date('Y-m-d H:i:sO');
        request("usuarios?id=eq.{$id_usuario}", "PATCH", [
            "codigo_verificacao" => $codigo,
            "codigo_criado_em"   => $agora,
        ]);

        /* Guarda contexto na sessão para verificar.act.php usar */
        $_SESSION['email_verificar'] = $emailAtual;
        $_SESSION['tipo_codigo']     = 'alterar_email';
        $_SESSION['novo_email']      = $valor;

        header("Location: ../verificar_acesso.php?etapa=aviso");
        exit;

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

    case 'imagem_perfil':
        if (!isset($_FILES['imagem']) || $_FILES['imagem']['error'] !== 0) {
            $_SESSION["mensagem"] = "Selecione uma imagem válida.";
            header("Location: ../usuario.php");
            exit;
        }

        $arquivo = $_FILES['imagem'];
        $tmp = $arquivo['tmp_name'];
        $nomeImg = $arquivo['name'];

        $bucket = $_ENV['BALDE'];
        $supabaseUrl = trim($_ENV['SUPABASE_URL']);
        $baldeKey = trim($_ENV['BALDE_KEY']);

        $buscaUsuario = request("usuarios?id=eq.{$id_usuario}&select=imagem");
        if (!empty($buscaUsuario) && !isset($buscaUsuario['error'])) {
            $urlAntiga = $buscaUsuario[0]['imagem'] ?? '';

            if (!empty($urlAntiga) && strpos($urlAntiga, 'deufalt.png') === false) {
                $partesUrl = explode("/$bucket/", $urlAntiga);
                $nomeArquivoAntigo = end($partesUrl);

                if (!empty($nomeArquivoAntigo)) {
                    $urlDelete = $supabaseUrl . "/storage/v1/object/$bucket/$nomeArquivoAntigo";
                    $chDel = curl_init($urlDelete);
                    curl_setopt_array($chDel, [
                        CURLOPT_CUSTOMREQUEST => "DELETE",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                        CURLOPT_HTTPHEADER => [
                            "Authorization: Bearer " . $baldeKey
                        ]
                    ]);
                    curl_exec($chDel);
                }
            }
        }

        $extensao = pathinfo($nomeImg, PATHINFO_EXTENSION);
        $nomeFinal = "avatar_" . $id_usuario . "_" . uniqid() . "." . $extensao;

        $urlUpload = $supabaseUrl . "/storage/v1/object/$bucket/$nomeFinal";
        $tipoMime = ($extensao == 'png') ? 'image/png' : 'image/jpeg';

        $ch = curl_init($urlUpload);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $baldeKey,
                "Content-Type: " . $tipoMime
            ],
            CURLOPT_POSTFIELDS => file_get_contents($tmp)
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $_SESSION["mensagem"] = "Erro CURL: " . curl_error($ch);
            header("Location: ../usuario.php");
            exit;
        }

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($status != 200 && $status != 201) {
            $_SESSION["mensagem"] = "Erro no upload (HTTP $status): $response";
            header("Location: ../usuario.php");
            exit;
        }

        $urlPublicaImagem = $supabaseUrl . "/storage/v1/object/public/$bucket/$nomeFinal";

        $dados = [
            "imagem" => $urlPublicaImagem
        ];

        $_SESSION['usuario_imagem'] = $urlPublicaImagem;
        break;
}

$sql = request("usuarios?id=eq.{$id_usuario}", "PATCH", $dados);

if (isset($sql['error'])) {
    $_SESSION["mensagem"] = "Erro ao atualizar " . ($tipo === 'imagem_perfil' ? 'foto de perfil' : $tipo);
} else {
    $_SESSION["mensagem"] = ($tipo === 'imagem_perfil' ? 'Foto de perfil' : ucfirst($tipo)) . " atualizada com sucesso!!";
}

header("Location: ../usuario.php");
exit;