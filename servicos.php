<?php
include('./includes/head.php');
include('./includes/topo.php');
include('./util/avisos.php');
?>

<main class="principal">
    <section class="avisos-eventos">
        <div class="reservados quadro">
            <h1>Reservados</h1>
            <div class="box">
                <?php
                $sqlReservados = "SELECT c.id AS id_contrato, c.dia, c.horario, c.confirmado, 
                                         s.nome, s.descricao, s.imagem 
                                  FROM contratados c 
                                  JOIN servicos s ON c.id_servico = s.id 
                                  WHERE c.id_cliente = :id 
                                  ORDER BY c.dia DESC";
                try {
                    $stmtRes = $con->prepare($sqlReservados);
                    $stmtRes->bindParam(':id', $id_usuario_logado);
                    $stmtRes->execute();

                    if ($stmtRes->rowCount() > 0) {
                        while ($res = $stmtRes->fetch(PDO::FETCH_ASSOC)) {
                            $horario = date('H:i', strtotime($res['horario']));
                            $dataRes = date('d/m/Y', strtotime($res['dia']));
                            $imgRes = !empty($res['imagem']) ? $res['imagem'] : './img/default-servico.jpg';

                            echo "<div class='card card-servico'>";
                            echo "<img src='" . htmlspecialchars($imgRes) . "' alt=''>";
                            echo "<div>";
                            echo "<div class='info-card'>";
                            echo "<h2 class='titulo-card'>" . htmlspecialchars($res['nome']) . "</h2>";
                            echo "<p>" . htmlspecialchars($res['descricao']) . "</p>";
                            echo "<small>Status: " . ucfirst($res['confirmado']) . "</small>";
                            echo "</div>";
                            echo "<div class='cronograma'>";
                            echo "<p>Agendado para as <time>$horario</time></p>";
                            echo "<p>Data: <time>$dataRes</time></p>";
                            echo "</div>";
                            echo "<div class='box-btn'>";
                            echo "<a href='remarcar.php?id={$res['id_contrato']}' class='btn'>Remarcar</a>";
                            echo "<a href='cancelar.php?id={$res['id_contrato']}' class='btn'>Cancelar</a>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<h2 class='aviso-vazio'>Você ainda não reservou nenhum serviço.</h2>";
                    }
                } catch (PDOException $e) {
                    echo "Erro ao carregar reservas.";
                }
                ?>
            </div>
        </div>

        <div class="avisos quadro">
            <h1>Quadro de Avisos</h1>
            <div class="box">
                <div class="card-avisos">
                    <div class="titulo-avisos">
                        <img src="./icon/icone.png" alt="">
                        <div>
                            <h2>Manutenção de Elevadores</h2>
                            <p>15/03/2026</p>
                        </div>
                    </div>
                    <p>Informamos que o elevador social do bloco A passará por manutenção preventiva das 14h às 16h.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="servicos">
        <h1>Serviços Disponíveis no Condomínio</h1>
        <section class="sessao-servicos">
            <?php
            $sqlTodos = "SELECT * FROM servicos WHERE codigo = :chave ORDER BY nome ASC";
            try {
                $stmtTodos = $con->prepare($sqlTodos);
                $stmtTodos->bindParam(':chave', $codigo_condominio);
                $stmtTodos->execute();

                if ($stmtTodos->rowCount() > 0) {
                    while ($servico = $stmtTodos->fetch(PDO::FETCH_ASSOC)) {
                        $horaInicio = date('H:i', strtotime($servico['horario_inicio']));
                        $horaFim = date('H:i', strtotime($servico['horario_fim']));
                        $imgServ = !empty($servico['imagem']) ? $servico['imagem'] : './img/default-servico.jpg';

                        echo "<div class='card card-servico'>";
                        echo "<img src='" . htmlspecialchars($imgServ) . "' alt=''>";
                        echo "<div>";
                        echo "<div class='info-card'>";
                        echo "<h2 class='titulo-card'>" . htmlspecialchars($servico['nome']) . "</h2>";
                        echo "<p>" . htmlspecialchars($servico['descricao']) . "</p>";
                        echo "</div>";
                        echo "<div class='cronograma'>";
                        echo "<p>Das <time>$horaInicio</time> Até <time>$horaFim</time></p>";
                        echo "</div>";
                        echo "<div class='box-btn'>";
                        echo "<a href='agendar.php?id={$servico['id']}' class='btn'>Agendar serviço</a>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<h2 class='aviso-vazio'>Nenhum serviço disponível para o seu condomínio.</h2>";
                }
            } catch (PDOException $e) {
                echo "Erro ao carregar serviços.";
            }
            ?>
        </section>
    </section>
</main>

<?php include "./includes/rodape.php"; ?>