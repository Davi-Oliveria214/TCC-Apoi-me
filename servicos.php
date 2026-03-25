<?php
include('./includes/head.php');
include('./includes/topo.php');
include('./util/avisos.php');
?>

<main class="principal">
    <section class="avisos-eventos">
        <div class="avisos-condominio quadro">
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

        <div class="reservados quadro">
            <h1>Reservados</h1>
            <div class="box">
                <?php
                $endpoint = "contratados?id_cliente=eq.$id&select=id,dia,hora,confirmado,id_servico,servicos(nome,descricao,imagem,id_prestador)&order=dia.desc";
                $sql = request($endpoint, "GET");

                if (!empty($sql) && !isset($sql['error'])) :
                    foreach ($sql as $res) :
                        $horario = date('H:i', strtotime($res['hora']));
                        $dataRes = date('d/m/Y', strtotime($res['dia']));
                        $status  = $res['confirmado'];
                        $idContrato = $res['id'];

                        $nomeServ = $res['servicos']['nome'];
                        $descricaoServ = $res['servicos']['descricao'];
                        $imgRes = !empty($res['servicos']['imagem']) ? $res['servicos']['imagem'] : './img/default-servico.jpg';
                ?>
                        <div class="card-reservados">
                            <div class="btn-img">
                                <img src="<?php echo $imgRes ?>" alt="Profissional">
                                <button class="btn-cancelar">Cancelar</button>
                            </div>

                            <div class="title-date">
                                <h3><?php echo $nomeServ ?></h3>
                                <p>Data: <time><?php echo $dataRes ?></time> <br> Hora: <time><?php echo $horario ?></time></p>

                                <div class="acoes-reservados">
                                    <a href="#" class="btn-link btn-detalhes">Ver Detalhes</a>
                                    <a href="./mensagens.php" class="btn-link btn-chat">Chat</a>
                                </div>
                            </div>
                        </div>
                <?php
                    endforeach;
                else :
                    echo "<h2 class='aviso-vazio'>Você ainda não reservou nenhum serviço.</h2>";
                endif;
                ?>
            </div>
        </div>
    </section>

    <section class="servicos-publicados">
        <h1>Serviços Disponíveis</h1>
        <section class="sessao-servicos">
            <?php
            $sql = request("servicos?codigo=eq.{$_SESSION['codigo']}&select=*&order=nome.asc");

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
                    echo "<div class='box-btn-servico'>";
                    echo "<a href='./agendar.php?id={$servico['id']}' class='btn'>Contratar</a>";
                    echo "<a href='./mensagens.php?id={$servico['id']}' class='btn'>Chat</a>";
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