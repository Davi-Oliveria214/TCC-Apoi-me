<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
        $stm = $con->prepare('SELECT COUNT(*) as total FROM servicos');
        $stm->execute();
        $res = $stm->get_result();
        $dados = $res->fetch_assoc();
        $total_no_banco = $dados['total'];

        if ($total_no_banco > 0) {
            $quantidade_para_sortear = min(10, $total_no_banco);
            $sorte = [];

            while (count($sorte) < $quantidade_para_sortear) {
                $n = rand(1, $total_no_banco);
                if (!in_array($n, $sorte)) {
                    $sorte[] = $n;
                }
            }

            $ids = implode(',', $sorte);

            $sql = "SELECT * FROM servicos WHERE id IN ($ids) ORDER BY FIELD(id, $ids)";
            $resultado_servicos = $con->query($sql);

            if ($resultado_servicos->num_rows > 0) {
                while ($servico = $resultado_servicos->fetch_assoc()) {
                    $horaInicio = date('H:i', strtotime($servico['horario_inicio']));
                    $horaFim = date('H:i', strtotime($servico['horario_fim']));

                    echo "<div class='card card-servico'>";
                    echo "<img src='" . htmlspecialchars($servico['imagem']) . "' alt=''>";
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
                echo "<h2 id='avisos'>Nenhum serviço encontrado</h2>";
            }
        } else {
            echo "<h2 id='avisos'>Nenhum serviço cadastrado</h2>";
        }
        ?>
    </section>
</main>

<?php
include('./includes/rodape.php');
?>