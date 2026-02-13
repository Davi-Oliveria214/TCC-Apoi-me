<?php
require("./includes/conexao.php");
include "./includes/cabecalho.php";
?>

<main class="principal">
    <section class="avisos-eventos">
        <div class="reservados quadro">
            <h1>Contratados</h1>
            <div class="box">
                <?php
                $servicos = mysqli_query($con, "SELECT * FROM contratados WHERE id_cliente = 1");

                if ($servicos->num_rows) {
                    while ($servico - mysqli_fetch_assoc($servicos)) {
                        $horario = date('H:i', strtotime($servico['horario']));

                        echo "<div class='card card-servico'>";
                        echo "<img src='$servico[imagem]' alt=''>";
                        echo "<div>";
                        echo "<div class='info-card'>";
                        echo "<h2 class='titulo-card'>$servico[nome]</h2>";
                        echo "<p>$servico[descricao]</p>";
                        echo "</div>";
                        echo "<div class='cronograma'>";
                        echo "<p>Das <time datetime='$horario'>$horario</time>";
                        echo "<p>Data limite: <time datetime='2026-07-01'>$servico[data]</time></p>";
                        echo "</div>";
                        echo "<div class='botoes-card'>";
                        echo "<a href='' class='btn'>Remarcar</a>";
                        echo "<a href='' class='btn'>Cancelar</a>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<h2 class=aviso>Nenhum serviço contratado</h2>";
                }
                ?>
            </div>
        </div>
        <div class="avisos quadro">
            <h1>Quadro de Avisos</h1>
            <div class="box">

            </div>
        </div>
    </section>

    <section class="servicos">
        <h1>Serviços</h1>
        <section class="sessao-servicos">
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
    </section>
</main>

<?php
include "./includes/rodape.php";
?>