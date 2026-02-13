<?php
require("./includes/conexao.php");
include("./includes/cabecalho.php");
?>
<div class="img-mostrar">
    <img src="./img/banner.png" alt="">
    <figcaption class="texto-img">
        <h1>Conectanto suas necessidades ao seu Bem-estar</h1>
    </figcaption>
</div>

<main class="principal-inicial">
    <section class="inicial-escolha" id="filtro">
        <ul>
            <?php
            $sql = mysqli_query($con, "SELECT * FROM categorias;");

            echo "<li onclick=filtrar(0)>Todos</li>";
            while ($categoria = mysqli_fetch_assoc($sql)) {
                echo "<li onclick=filtrar($categoria[id])>$categoria[nome]</li>";
            }
            ?>
        </ul>
    </section>

    <section class="informacoes-inicial" id="todos-servicos">
        <?php
        $servicos = mysqli_query($con, "SELECT * FROM servicos");

        while ($servico = mysqli_fetch_assoc($servicos)) {
            $horaInicio = date('H:i', strtotime($servico['horario_inicio']));
            $horaFim = date('H:i', strtotime($servico['horario_fim']));

            echo "<div class='card card-servico'>";
            echo "<img src='$servico[imagem]' alt=''>";
            echo "<div>";
            echo "<div class='info-card'>";
            echo "<h2 class='titulo-card'>$servico[nome]</h2>";
            echo "<p>$servico[descricao]</p>";
            echo "</div>";
            echo "<div class='cronograma'>";
            echo "<p>Das <time datetime='$horaInicio'>$horaInicio</time>
                    Até <time datetime='$horaFim'>$horaFim</time></p>";
            echo "<p>Data limite: <time datetime='$servico[data_limite]'>$servico[data_limite]</time></p>";
            echo "</div>";
            echo "<div class='botoes-card'>";
            echo "<a href='' class='btn' >Agendar serviço</a>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        } ?>
    </section>
</main>
<?php include("./includes/rodape.php"); ?>
</body>

</html>