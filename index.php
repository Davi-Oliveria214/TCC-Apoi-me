<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Certifique-se de que o head.php ou topo.php incluam a sua conexão PDO $con
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
            /* SORTEIO NO POSTGRESQL:
               No PostgreSQL, usamos 'ORDER BY RANDOM()' para embaralhar os resultados.
               Isso substitui toda a lógica de gerar IDs aleatórios no PHP.
            */
            $sqlServ = "SELECT * FROM servicos ORDER BY RANDOM() LIMIT 10";
            $stmtServ = $con->query($sqlServ);
            $servicos = $stmtServ->fetchAll(PDO::FETCH_ASSOC);

            if (count($servicos) > 0) {
                foreach ($servicos as $servico) {
                    // Formatação de horas
                    $horaInicio = date('H:i', strtotime($servico['horario_inicio']));
                    $horaFim = date('H:i', strtotime($servico['horario_fim']));
                    
                    // Imagem padrão caso o banco esteja vazio
                    $imagem = !empty($servico['imagem']) ? htmlspecialchars($servico['imagem']) : './img/default-servico.jpg';

                    echo "<div class='card card-servico'>";
                    echo "<img src='$imagem' alt='" . htmlspecialchars($servico['nome']) . "'>";
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
        } catch (PDOException $e) {
            echo "<h2 id='avisos'>Erro ao carregar os serviços.</h2>";
        }
        ?>
    </section>
</main>

<?php
include('./includes/rodape.php');
?>