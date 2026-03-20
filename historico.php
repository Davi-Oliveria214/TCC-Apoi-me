<?php
include('./includes/head.php');
include('./includes/topo.php');
?>

<main class="main-historico">
    <section class="historico-contratado historico-box">
        <h1>Histórico de Contratação</h1>
        <div>
            <?php
            $servicos = request("servicos?select=*&limit=10", "GET");

            if (!empty($servicos) && !isset($servicos['error'])) {
                shuffle($servicos);

                foreach ($servicos as $servico) {
                    $horaInicio = date('H:i', strtotime($servico['hora_inicio']));
                    $horaFim = date('H:i', strtotime($servico['hora_fim']));
                    $imagem = !empty($servico['imagem']) ? $servico['imagem'] : './img/default.jpg';

                    echo "<div class='card card-servico' data-id='" . $servico['id'] . "'>";
                    echo "<img src='$imagem' alt=''>";
                    echo "<div>";
                    echo "<div class='info-card'>";
                    echo "<h2 class='titulo-card'>" . htmlspecialchars($servico['nome']) . "</h2>";
                    echo "<p>" . htmlspecialchars($servico['descricao']) . "</p>";
                    echo "<span>Horário: $horaInicio às $horaFim</span>";
                    echo "</div>";
                    echo "<div class='box-btn'>";
                    echo "<a href='./controls/agendar.php?id=" . $servico['id'] . "' class='btn'>Avaliar serviço</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<h2 id='aviso' style='text-align: center;'>Nenhum serviço disponível no momento.</h2>";
            }
            ?>
        </div>
    </section>
    <section class="historico-vendas historico-box">
        <h1>Histórico de vendas</h1>
        <div>
            <?php
            $servicos = request("servicos?select=*&limit=10", "GET");

            if (!empty($servicos) && !isset($servicos['error'])) {
                shuffle($servicos);

                foreach ($servicos as $servico) {
                    $horaInicio = date('H:i', strtotime($servico['hora_inicio']));
                    $horaFim = date('H:i', strtotime($servico['hora_fim']));
                    $imagem = !empty($servico['imagem']) ? $servico['imagem'] : './img/default.jpg';

                    echo "<div class='card card-servico' data-id='" . $servico['id'] . "'>";
                    echo "<img src='$imagem' alt=''>";
                    echo "<div>";
                    echo "<div class='info-card'>";
                    echo "<h2 class='titulo-card'>" . htmlspecialchars($servico['nome']) . "</h2>";
                    echo "<p>" . htmlspecialchars($servico['descricao']) . "</p>";
                    echo "<span>Horário: $horaInicio às $horaFim</span>";
                    echo "</div>";
                    echo "<div class='box-btn'>";
                    echo "<a href='./controls/agendar.php?id=" . $servico['id'] . "' class='btn'>Avaliar serviço</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<h2 id='aviso' style='text-align: center;'>Nenhum serviço disponível no momento.</h2>";
            }
            ?>
        </div>
    </section>
</main>