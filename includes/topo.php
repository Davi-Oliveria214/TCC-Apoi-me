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

    <?php
    if (!empty($_SESSION["id"])):
        $id = $_SESSION["id"];

        $res = request("usuario?id=eq.$id&select=nome,email,imagem,codigo", "GET");

        if (!empty($res) && !isset($res['error'])) {
            $usuario = $res[0];

            $nome = $usuario['nome'];
            $email = $usuario['email'];
            $img = $usuario['imagem'];
            $codigo =  $usuario['codigo'];
        }
    ?>

        <input type="text" class="pesquisa" placeholder="Pesquisar">

        <div class="user">
            <p>Olá, <?= htmlspecialchars($nome) ?></p>

            <a href="usuario.php">
                <img src="<?= htmlspecialchars($img) ?>"
                    alt="Usuário"
                    class="<?= ($pag === "usuario.php") ? "user-icon" : "user-img"; ?>">
            </a>

            <a href="#">
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
    <div>
        <ul>
            <li><a href="index.php">Início</a></li>

            <?php if (!empty($_SESSION["login"])): ?>
                <li><a href="servicos.php">Serviços</a></li>
                <a href="./includes/logout.php" class="sair-logout">Sair</a>
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