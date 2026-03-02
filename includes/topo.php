<?php
require('./includes/conexao.php');
@session_start();
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
            <?php
            if (isset($_SESSION["login"]) && $_SESSION["login"]) {
                echo "<li><a href='./servicos.php'>Serviços</a></li>";
            } else {
                echo $pag !== "/login.php" ? "<li><a href='login.php'>Login</a></li>" : "<li><a href='cadastro.php'>Cadastro</a></li>";
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
    if ($classe === "menu" && isset($_SESSION["id"])) {
        $id = $_SESSION["id"];

        $sql = "SELECT nome, email, imagem FROM usuario WHERE id = ?";

        $stm = $con->prepare($sql);
        $stm->bind_param("i", $id);
        $stm->execute();
        $resultado = $stm->get_result();

        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();

            $nome = $usuario["nome"];
            $email = $usuario["email"];
            $img = $usuario["imagem"];
        } else {
            $_SESSION["mensagem"] = "Usuário desconectado ou não existe";
            $_SESSION["login"] = false;
            $_SESSION["tipo"] = "desconectado";
        }
    ?>
        <input type="text" name="" id=" " class="pesquisa" placeholder="Pesquisar">


        <div class="user">
            <p>Olá, <?php echo $nome ?></p>
            <a href="./usuario.php"><img src="<?php echo $img ?>" alt="" class="<?php echo $userClass = ($pag === "/usuario") ? "user-icon" : "user-img"; ?>"></a>
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