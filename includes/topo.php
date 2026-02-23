<?php
$pag = $_SERVER['PHP_SELF'];

$classe = "";

if ($pag === "/login.php" || $pag === "/cadastro.php" || $pag === "/index.php" || $pag === "/criarChave.php") {
    $classe = "navegacao";
} else {
    $classe = "menu";
}
?>

<header class="topo-cabecalho" id="topo">
    <a class="img-logo" href="index.php"><img src="./img/condomino.png" alt=""></a>

    <nav class="<?php echo $classe; ?>">
        <ul>
            <li><a href="./index.php">Inicio</a></li>
            <li><a href="./servicos.php">Serviços</a></li>
            <?php
            if ($pag !== "/login.php") {
                echo "<li><a href='login.php'>Login</a></li>";
            }
            if ($pag !== "/cadastro.php") {
                echo "<li><a href='cadastro.php'>Cadastro</a></li>";
            }
            ?>
            <li><a href="#">Contato</a></li>
        </ul>
    </nav>

    <?php
    if ($pag === '/index.php') {
    ?>
        <style>
            #topo {
                background-color: transparent;
            }
        </style>
    <?php
    }
    ?>

    <?php
    if ($classe === "menu") {
    ?>
        <input type="text" name="" id="" class="pesquisa" placeholder="Pesquisar">


        <div class="user">
            <p>Olá, Usuário</p>

            <?php
            if ($pag === "/usuario.php") {
                $userClass = "user-icon";
            } else {
                $userClass = "user-img";
            }
            ?>
            <a href="./usuario.php"><img src="./icon/user.png" alt="" class="<?php echo $userClass ?>"></a>
            <a href=""><img src="./icon/msg.png" alt=""></a>
        </div>
        <nav id="burguer">
            <div></div>
            <div></div>
            <div></div>
        </nav>

    <?php
    }
    ?>

</header>