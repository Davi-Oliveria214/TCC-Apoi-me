<?php
include('./includes/head.php');
include('./includes/topo.php');
?>

<main class="main-historico">
    <section class="historico-contratado historico-box">
        <h1>Histórico de Contratação</h1>
        <div>
            <?php
            $servicos = request(
                "contratados?select=id,hora,dia,confirmado,servicos(id,nome,descricao,imagem)&id_cliente=eq.$id&avaliar=eq.false",
                "GET"
            );

            if (!empty($servicos) && !isset($servicos['error'])) :
                shuffle($servicos);

                foreach ($servicos as $servico) :
                    $hora = date('H:i', strtotime($servico['hora']));
            ?>
                    <div class='card-historico' data-id='<?php echo $servico["id"] ?>'>
                        <img src='<?php echo $servico['servicos']['imagem'] ?>' alt=''>
                        <div>
                            <div class='info-card'>
                                <h2 class='titulo-card'><?php echo $servico['servicos']['nome'] ?></h2>
                                <p><?php echo $servico['servicos']['descricao'] ?></p>
                                <span><?php echo $hora ?></span>
                            </div>
                            <div class='box-btn'>
                                <button type="button" onclick="abrirAvaliar('<?php echo $servico['servicos']['id'] ?>', '<?php echo $servico['servicos']['nome'] ?>', '<?php echo $servico['servicos']['imagem'] ?>', '<?php echo $servico['dia'] ?>', '<?php echo $servico['hora'] ?>', '<?php echo $servico['confirmado'] ?>', '<?php echo $servico['id'] ?>')" class="btn">Avaliar serviço</button>
                            </div>
                        </div>
                    </div>
            <?php
                endforeach;
            else :
                echo "<h2 id='aviso' style='text-align: center;'>Nenhum serviço contratado no momento.</h2>";
            endif;
            ?>
        </div>
    </section>
    <section class="historico-vendas historico-box">
        <h1>Histórico de vendas</h1>
        <div>
            <?php
            $servicos = request(
                "contratados?select=id,hora,dia,confirmado,servicos(nome,descricao,imagem)&id_prestador=eq.$id",
                "GET"
            );

            if (!empty($servicos) && !isset($servicos['error'])) :
                shuffle($servicos);

                foreach ($servicos as $servico) :
                    $hora = date('H:i', strtotime($servico['hora']));
                    $imagem = $servico['servicos']['imagem'];
            ?>
                    <div class='card-historico' data-id='<?php echo $servico['servicos']["id"] ?>'>
                        <img src='<?php echo $servico['servicos']['imagem'] ?>' alt=''>
                        <div>
                            <div class='info-card'>
                                <h2 class='titulo-card'><?php echo $servico['servicos']['nome'] ?></h2>
                                <p><?php echo $servico['servicos']['descricao'] ?></p>
                                <span><?php echo $hora ?></span>
                            </div>
                            <div class='box-btn'>
                                <button class='btn' onclick="abrirAvaliar('<?php echo $servico['id'] ?>', '<?php echo $servico['servicos']['nome'] ?>', '<?php echo $servico['servicos']['imagem'] ?>', '<?php echo $servico['dia'] ?>', '<?php echo $servico['hora'] ?>', '<?php echo $servico['confirmado'] ?>', '<?php echo $servico['id'] ?>')">Avaliar serviço</button>
                            </div>
                        </div>
                    </div>
            <?php
                endforeach;
            else :
                echo "<h2 id='aviso' style='text-align: center;'>Nenhum serviço agendado no momento.</h2>";
            endif;
            ?>
        </div>
    </section>
    <section class="historico-avaliados historico-box">
        <h1>Histórico de Avaliados</h1>
        <div>
            <?php
            $servicos = request(
                "contratados?select=id,hora,dia,confirmado,servicos(nome,descricao,imagem),avaliacao(nota,comentario)&id_cliente=eq.$id&avaliar=eq.true",
                "GET"
            );

            if (!empty($servicos) && !isset($servicos['error'])) :
                shuffle($servicos);

                foreach ($servicos as $servico) :
                    $hora = date('H:i', strtotime($servico['hora']));
                    $imagem = $servico['servicos']['imagem'];
            ?>
                    <div class='card-historico' data-id='<?php echo $servico["id"] ?>'>
                        <img src='<?php echo $servico['servicos']['imagem'] ?>' alt=''>
                        <div>
                            <div class='info-card'>
                                <h2 class='titulo-card'><?php echo $servico['servicos']['nome'] ?></h2>
                                <p><?php echo $servico['servicos']['descricao'] ?></p>
                                <span><?php echo $hora ?></span>
                            </div>
                            <div class='box-btn'>
                                <button class='btn' onclick="verAvaliacao(
                                            '<?php echo $servico['servicos']['nome'] ?>',
                                            '<?php echo $imagem ?>',
                                            '<?php echo addslashes($servico['avaliacao'][0]['comentario'] ?? '') ?>',
                                            '<?php echo $servico['avaliacao'][0]['nota'] ?>',
                                            '<?php echo $servico['id'] ?>'
                                        )">
                                    Ver avaliação
                                </button>
                            </div>
                        </div>
                    </div>
            <?php
                endforeach;
            else :
                echo "<h2 id='aviso' style='text-align: center;'>Nenhum serviço avaliado no momento.</h2>";
            endif;
            ?>
        </div>
    </section>
</main>

<?php include("./includes/rodape.php"); ?>