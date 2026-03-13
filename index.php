<?php
include('./includes/head.php');
include('./includes/topo.php');
?>

<figure class="img-mostrar">
    <img src="./img/imagemcentrodecond.jpg" alt="Centro de Condomínio">
    <figcaption class="texto-img">
        <h1>Conectando suas necessidades ao seu Bem-estar</h1>
    </figcaption>
</figure>

<main class="principal-inicial">
    <section class="inicial-escolha" id="filtro">
        <ul>
            <?php
            echo "<li onclick='filtrar(0)'>Todos</li>";

            $categorias = request("categorias?select=id,nome&order=nome.asc", "GET");

            if (!empty($categorias) && !isset($categorias['error'])) {
                foreach ($categorias as $cate) {
                    $idCate = $cate['id'];
                    $nomeCate = htmlspecialchars($cate['nome']);
                    echo "<li onclick='filtrar($idCate)'>$nomeCate</li>";
                }
            }
            ?>
        </ul>
    </section>

    <section class="informacoes-inicial" id="todos-servicos">
        <?php
        $servicos = request("servicos?select=*&limit=10", "GET");

        if (!empty($servicos) && !isset($servicos['error'])) {
            foreach ($servicos as $servico) {
                $horaInicio = date('H:i', strtotime($servico['horario_inicio']));
                $horaFim = date('H:i', strtotime($servico['horario_fim']));

                $imagem = !empty($servico['imagem']) ? $servico['imagem'] : './img/default.jpg';
                echo "<div class='card card-servico'>";
                echo "<img src='$imagem' alt=''>";
                echo "<div>";
                echo "<div class='info-card'>";
                echo "<h2 class='titulo-card'>" . htmlspecialchars($servico['nome']) . "</h2>";
                echo "<p>" . htmlspecialchars($servico['descricao']) . "</p>";
                echo "<span>Horário: $horaInicio às $horaFim</span>";
                echo "</div>";
                echo "<div class='box-btn'>";
                echo "<a href='./controls/agendar.php?id=" . $servico['id'] . "' class='btn'>Agendar serviço</a>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<h2 id='avisos'>Nenhum serviço disponível no momento.</h2>";
        }
        ?>
    </section>
</main>
<?php
include('./includes/rodape.php');
?>