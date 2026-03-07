<?php
include('./includes/head.php');
include('./includes/topo.php');
?>


<figure class="img-mostrar">
    <img src="./img/imagemcentrodecond.jpg" alt="">
    <figcaption class="texto-img">
        <h1>Conectanto suas necessidades ao seu Bem-estar</h1>
    </figcaption>
</figure>

<main class="principal-inicial">
    <section class="inicial-escolha" id="filtro">
        <ul>
            <?php
            echo "<li onclick=filtrar(0)>Todos</li>";
            $categorias = mysqli_query($con, "SELECT * FROM categorias");

            if ($categorias->num_rows) {
                while ($categoria = mysqli_fetch_assoc($categorias)) {
                    echo "<li onclick=filtrar($categoria[id])>$categoria[nome]</li>";
                }
            }
            ?>
        </ul>
    </section>

    <section class="informacoes-inicial" id="todos-servicos">
        <?php
        $servicos = mysqli_query($con, "SELECT * FROM servicos LIMIT 10");

        if ($servicos->num_rows) {
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
                echo "<div class='box-btn'>";
                echo "<a href='' class='btn'>Agendar serviço</a>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<h2 id=avisos>Nenhum serviço encontrado</h2>";
        }
        ?>
    </section>
</main>

<?php
include('./includes/rodape.php');
?>