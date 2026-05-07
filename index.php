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
            <li onclick="filtro(this,'servicos',0)">Todos</li>
            <?php
            $categorias = request("categorias?select=id,nome&order=nome.asc", "GET");

            if (!empty($categorias) && !isset($categorias['code']) && !isset($categorias['error'])) {
                foreach ($categorias as $cate) {
                    $idCate = $cate['id'];
                    $nomeCate = htmlspecialchars($cate['nome']);
            ?>
                    <li onclick="filtro(this,'servicos',<?php echo $idCate ?>)"><?php echo $nomeCate ?></li>
            <?php
                }
            }
            ?>
        </ul>
    </section>

    <section class="informacoes-inicial local-filtro" id="todos-servicos">
        <?php
        $servicos = request("servicos?status=eq.true&select=*&order=criado.desc&limit=10", "GET");

        if (!empty($servicos) && !isset($servicos['error'])) :
            shuffle($servicos);
            foreach ($servicos as $servico) :
                $horaInicio = date('H:i', strtotime($servico['hora_inicio']));
                $horaFim = date('H:i', strtotime($servico['hora_fim']));
                $duracao = date('H:i', strtotime($servico['duracao']));
                $imagem = $servico['imagem'];
        ?>
                <div class='card card-servico' data-id='<?php echo $servico["id"] ?>'>
                    <img src='<?php echo $imagem ?>' alt=''>
                    <div>
                        <div class='info-card'>
                            <h2 class='titulo-card'><?php echo $servico['nome'] ?></h2>
                            <p><?php echo $servico['descricao'] ?></p>
                            <span><?php echo $horaInicio ?></span>
                        </div>
                        <?php if (empty($id) || $id != $servico['id_prestador']): ?>
                            <button onclick="<?php echo isset($id) ? "abrirModalAgendar('{$servico['id']}')" : "window.location.href = './util/setAviso.php';" ?>" class="btn">
                                Agendar serviço
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
        <?php
            endforeach;
        else :
            echo "<h2 id='aviso' style='text-align: center;'>Nenhum serviço disponível no momento.</h2>";
        endif;
        ?>
    </section>

    <section class="parceiros-principal" id="publicidade">
        <h2>Publicidade</h2>
        <div>
            <article class="card-publicidade">
                <img class="menu-img" src="./img/condomino.png" alt="">
                <div>
                    <h3>Empresa parceira</h3>
                    <p>Serviços especializados para sua casa</p>
                </div>
            </article>
            <article class="card-publicidade">
                <img class="menu-img" src="./img/condomino.png" alt="">
                <div>
                    <h3>Empresa parceira</h3>
                    <p>Serviços especializados para sua casa</p>
                </div>
            </article>
        </div>
    </section>
</main>

<?php include('./includes/rodape.php'); ?>