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
            <li><a href="">Sobre</a></li>
            <li><a href="./servicos.php">Serviços</a></li>
            <?php
            if ($pag !== "/login.php") {
                echo "<li><a href='login.php'>Logar</a></li>";
            }
            if ($pag !== "/cadastro.php") {
                echo "<li><a href='cadastro.php'>Cadastrar</a></li>";
            }
            ?>
        </ul>
    </nav>

    <?php
    $pag = $_SERVER['PHP_SELF'];
    if ($pag === '/index.php') {
        echo "<nav class= 'navegacao'>";
        echo "<ul class='links-sociais'>";
        echo "<li><a href=''><img src='./icon/instagram-icon.png' alt=''></a></li>";
        echo "<li><a href=''><img src='./icon/youtube-icon.png' alt=''></a></li>";
        echo "</ul>";
        echo "</nav>";
        ?>
        <style>
            #topo {
                background-color: transparent;
            }
        </style>
        <?php
    }
    ?>
</header>