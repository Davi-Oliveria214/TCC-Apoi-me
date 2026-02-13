<?php
require("./includes/conexao.php");
include("./includes/cabecalho.php");
?>

<style>
    /* PÁGINA INICIAL */
    .img-mostrar {
        width: 100vw;
        height: 60vh;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
    }

    .img-mostrar>img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        z-index: -1;
    }

    .texto-img>h1 {
        line-height: 1.3;
        text-align: center;
        word-break: break-all;
        font-size: clamp(0.5rem, 1rem + 3vw, 3.5rem);
        color: var(--branco);
        background-color: rgba(var(--preto-rgba), 0.5);
        padding: 15px 25px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(var(--preto-rgba), 0.5);
        border: 1px solid var(--dourado-palido);
    }

    .principal-inicial {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 30px;
        padding: 20px 0;
    }

    /* ESCOLHAS (Filtros) */
    .inicial-escolha {
        width: 100vw;
        display: flex;
        justify-content: center;
        padding: 0 10px;
    }

    .inicial-escolha>ul {
        width: 100%;
        max-width: 1200px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: var(--verde-musgo);
        padding: 15px;
        gap: 10px;
        overflow-x: auto;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .inicial-escolha li {
        min-width: 150px;
        max-height: 50px;
        text-align: center;
        cursor: pointer;
        font-size: 1rem;
        padding: 10px 15px;
        border-radius: 20px;
        background-color: var(--verde-musgo-medio);
        color: var(--dourado-fraco);
        transition: background-color 0.3s, transform 0.2s, box-shadow 0.3s;
        flex-shrink: 0;
    }

    .inicial-escolha li:hover {
        background-color: var(--dourado-palido);
        color: var(--verde-musgo);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    /* INFORMAÇÕES INICIAL (Container dos Cards) */
    .informacoes-inicial {
        width: 90%;
        max-width: 1200px;
        max-height: 430px;
        display: flex;
        justify-content: center;
        padding: 25px 15px;
        gap: 40px;
        flex-wrap: wrap;
        overflow-y: auto;
        border-radius: 15px;
        background-color: rgba(var(--dourado-palido-rgba), 0.3);
        box-shadow: 0 10px 20px rgba(var(--preto-rgba), 0.2);
        border: none;
        overflow-y: scroll;
    }

    /* PARCEIROS */
    .parceros-principal {
        width: 100vw;
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
        padding: 20px 10px;
    }

    .parceros-principal>img {
        width: 85%;
        max-width: 1000px;
        height: 35vh;
        object-fit: cover;
        border-radius: 10px;
    }

    @media screen and (max-width: 700px) {
        .img-mostrar {
            height: 35vh;
            /* Aumenta a altura no mobile */
        }

        .img-mostrar>img {
            height: 100%;
        }

        .texto-img>h1 {
            padding: 10px 20px;
        }
    }
</style>

<section id="modelo" class="modal">
    <aside class="modelo-card">
        <span id="fecharModelo" class="fechar" onclick="modelo('fechar')">&times;</span>
        <h2>Nome do Serviço</h2>
        <section class="info-modelo">
            <img src="./img/cuidador-de-cachorro.jpg" alt="" class="img-servico">
            <div class="informacoes-servico">
                <div class="sobre-servico">
                    <h3>Sobre o serviço</h3>
                    <p>Este card foi criado para fornecer aos usuários uma experiência clara e organizada,
                        permitindo
                        que mensagens importantes sejam destacadas de forma elegante. Aqui você pode colocar
                        instruções
                        detalhadas, avisos, dicas de uso ou qualquer conteúdo que mereça atenção imediata.</p>
                </div>

                <div class="necessidade-servico">
                    <h4>Requisitos: </h4>
                    <h4>Experiência: </h4>
                    <h4>Pagamento: </h4>
                </div>
            </div>
        </section>

        <div class="botoes">
            <button>Aceitar</button>
            <button>Ver sobre</button>
        </div>
    </aside>
</section>

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
            $sql = mysqli_query($con, "SELECT * FROM categorias;");

            echo "<li onclick=filtrar(0)>Todos</li>";
            while ($categoria = mysqli_fetch_assoc($sql)) {
                echo "<li onclick=filtrar($categoria[id])>$categoria[nome]</li>";
            }
            ?>
        </ul>
    </section>

    <section class="informacoes-inicial" id="todos-servicos">
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

    <section class="parceros-principal">
        <img class="menu-img" src="./img/condomino.png" alt="">
    </section>
</main>
<?php include("./includes/rodape.php"); ?>
</body>

</html>