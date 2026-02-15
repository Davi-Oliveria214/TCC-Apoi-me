<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./icon/icone.phg">
    <link rel="stylesheet" href="./css/style.css">
    <script src="./libs/jquery.js"></script>
    <script src="./js/app.js" defer></script>
    <title>TCC</title>
</head>

<body>
    <header class="topo-cabecalho" id="topo">
        <a class="img-logo" href="index.php"><img src="./img/condomino.png" alt=""></a>

        <nav class="navegacao">
            <ul>
                <li><a href="">Sobre</a></li>
                <li><a href="">Serviços</a></li>
                <?php
                $pag = $_SERVER['PHP_SELF'];
                if ($pag !== "/login.php") {
                    echo "<li><a href='login.php'>Logar</a></li>";
                }
                if ($pag !== "/cadastro.php") {
                    echo "<li><a href=''>Cadastrar</a></li>";
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
        }
        ?>
    </header>