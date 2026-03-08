<?php
require('./conexao.php');
@session_start();

$pag = basename($_SERVER['PHP_SELF']);

$classe = "navegacao";
?>

<header class="topo-cabecalho" id="topo">

    <a class="img-logo" href="index.php">
        <img src="./img/condomino.png" alt="Logo">
    </a>

    <?php
    if (!empty($_SESSION["id"])):

        $id = $_SESSION["id"];

        $sql = "SELECT nome, email, imagem, codigo FROM usuario WHERE id = ?";
        $stm = $con->prepare($sql);
        $stm->bind_param("i", $id);
        $stm->execute();
        $resultado = $stm->get_result();

        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            $nome = $usuario["nome"];
            $img = $usuario["imagem"];
            $codigo = $usuario["codigo"];
        } else {
            $_SESSION["login"] = false;
            session_destroy();
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

    <nav id="burguer">
        <div></div>
        <div></div>
        <div></div>
    </nav>
</header>

<nav class="<?php echo $classe ?> desativado" id="nav-id">
    <div>
        <a class="img-logo" href="index.php">
            <img src="./img/condomino.png" alt="Logo">
        </a>

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

            <li><a href="#">Contato</a></li>
            <li><a href="sobre.php">Sobre</a></li>
        </ul>
    </div>
</nav>