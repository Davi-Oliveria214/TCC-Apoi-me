<?php
require_once(__DIR__ . '/../conexao.php');
@session_start();

$pag = basename($_SERVER['PHP_SELF']);

$classe = "navegacao";
include('./util/avisos.php');
?>

<header class="topo-cabecalho" id="topo">

    <a class="img-logo" href="index.php">
        <img src="./img/condomino.png" alt="Logo">
    </a>

    <input type="text" class="pesquisa" placeholder="Pesquisar">

    <?php
    if (!empty($_SESSION["id"])):
        $id = $_SESSION["id"];

        $res = request("usuarios?id=eq.$id&select=nome,email,imagem,codigo,telefone", "GET");

        if (!empty($res) && !isset($res['error'])) {
            $usuario = $res[0];

            $nome = $usuario['nome'];
            $email = $usuario['email'];
            $telefone = $usuario['telefone'];
            $img = $usuario['imagem'];
            $_SESSION['codigo'] =  $usuario['codigo'];
        }
    ?>
        <div class="user">
            <p>Olá, <?= htmlspecialchars($nome) ?></p>

            <a href="usuario.php">
                <img src="<?= htmlspecialchars($img) ?>"
                    alt="Usuário">
            </a>

            <a href="../mensagens.php">
                <img src="./icon/msg.png" alt="Mensagens">
            </a>
        </div>
    <?php endif; ?>

    <nav id="burguer" class="<?php echo isset($_SESSION['login']) ? 'topoLogado' : '' ?>">
        <div></div>
        <div></div>
        <div></div>
    </nav>
</header>

<nav class="<?php echo $classe ?> desativado" id="nav-id">
    <div onclick="event.stopPropagation()">
        <ul>
            <li><a href="index.php">Início</a></li>

            <?php if (!empty($_SESSION["login"])): ?>
                <li><a href="servicos.php">Serviços</a></li>
                <li><a href="../mensagens.php">Chat</a></li>
                <li><a href="../anunciar.php">Anunciar</a></li>
                <li class="sair-logout"><a href="./includes/logout.php">Sair</a></li>
                <li class="historico"><a href="../historico.php">Histórico</a></li>
            <?php else: ?>
                <?php if ($pag === "login.php"): ?>
                    <li><a href="cadastro.php">Cadastro</a></li>
                <?php elseif ($pag === "cadastro.php"): ?>
                    <li><a href="login.php">Login</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="cadastro.php">Cadastro</a></li>
                <?php endif; ?>
            <?php endif; ?>

            <li><a href="../contato.php">Contato</a></li>
            <li><a href="../sobre.php">Sobre</a></li>
        </ul>
    </div>
</nav>