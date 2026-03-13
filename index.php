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
            // Opção padrão
            echo "<li onclick='filtrar(0)'>Todos</li>";

            // BUSCA DE CATEGORIAS (Corrigido para PDO)
            $sqlCat = "SELECT id, nome FROM categorias ORDER BY nome ASC";
            try {
                $stmtCat = $con->query($sqlCat);
                while ($categoria = $stmtCat->fetch(PDO::FETCH_ASSOC)) {
                    $idCat = $categoria['id'];
                    $nomeCat = htmlspecialchars($categoria['nome']);
                    echo "<li onclick='filtrar($idCat)'>$nomeCat</li>";
                }
            } catch (PDOException $e) {
                // Erro silencioso ou log
            }
            ?>
        </ul>
    </section>

    <section class="informacoes-inicial" id="todos-servicos">
        <?php
        try {
            $sqlServ = "SELECT * FROM servicos ORDER BY RANDOM() LIMIT 10";
            $stmtServ = $con->query($sqlServ);
            $stmtServ->execute();

            if ($stmtServ) {
                while ($servico = $stmtServ->fetch(PDO::FETCH_ASSOC)) {
                    $horaInicio = date('H:i', strtotime($servico['horario_inicio']));
                    $horaFim = date('H:i', strtotime($servico['horario_fim']));

                    echo "<div class='card card-servico'>";
                    echo "<img src='$servico[imagem]' alt=''>";
                    echo "<div>";
                    echo "<div class='info-card'>";
                    echo "<h2 class='titulo-card'>$servico[nome]</h2>";
                    echo "<p>$servico[descricao]</p>";
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
        } catch (PDOException $e) {
            echo "<h2 id='avisos'>Erro ao carregar os serviços.</h2>";
        }
        ?>
    </section>
</main>

<?php
include('./includes/rodape.php');
?>