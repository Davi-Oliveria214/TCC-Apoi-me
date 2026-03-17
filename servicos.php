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
                $endpoint = "contratados?id_cliente=eq.$id&select=id,dia,hora,confirmado,id_servico(nome,descricao,imagem,id_prestador)&order=dia.desc";
                $sql = request($endpoint, "GET");

                if (!empty($sql) && !isset($sql['error'])) {
                    foreach ($sql as $res) {
                        $horario = date('H:i', strtotime($res['hora']));
                        $dataRes = date('d/m/Y', strtotime($res['dia']));
                        $status  = $res['confirmado'];
                        $idContrato = $res['id'];

                        $nomeServ = $res['servicos']['nome'];
                        $descricaoServ = $res['servicos']['descricao'];
                        $imgRes = !empty($res['servicos']['imagem']) ? $res['servicos']['imagem'] : './img/default-servico.jpg';

                        echo "<div class='card card-servico'>";
                        echo "<img src='" . htmlspecialchars($imgRes) . "' alt=''>";
                        echo "<div>";
                        echo "<div class='info-card'>";
                        echo "<h2 class='titulo-card'>" . htmlspecialchars($nomeServ) . "</h2>";
                        echo "<p>" . htmlspecialchars($descricaoServ) . "</p>";
                        echo "<small>Status: " . ucfirst($status) . "</small>";
                        echo "</div>";
                        echo "<div class='cronograma'>";
                        echo "<p>Agendado para as <time>$horario</time></p>";
                        echo "<p>Data: <time>$dataRes</time></p>";
                        echo "</div>";
                        echo "<div class='box-btn'>";
                        echo "<a href='remarcar.php?id=$idContrato' class='btn'>Remarcar</a>";
                        echo "<a href='cancelar.php?id=$idContrato' class='btn'>Cancelar</a>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<h2 class='aviso-vazio'>Você ainda não reservou nenhum serviço.</h2>";
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
            $sql = request("servicos?codigo=eq.$codigo&select=*&order=nome.asc");

            if (!empty($sql) && !isset($sql['error'])) {
                foreach ($sql as $servico) {
                    $horaInicio = date('H:i', strtotime($servico['hora_inicio']));
                    $horaFim = date('H:i', strtotime($servico['hora_fim']));
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
            ?>
        </section>
    </section>
</main>

<?php include "./includes/rodape.php"; ?>