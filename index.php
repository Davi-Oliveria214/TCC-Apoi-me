<?php
require('./includes/conexao.php');
include('./includes/head.php');
include('./includes/topo.php');
?>

<img src="./img/banner.png" alt="" class="img-sobre" id="banner">

<div class="titulo-texto-sobre" id="inicial">
    <h1>A solução mora ao lado</h1>
    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Error dolorem animi fugit modi at natus obcaecati
        fuga facilis reprehenderit aliquam excepturi minus, reiciendis repudiandae fugiat beatae rem veritatis ab
        cum.</p>
</div>


<main id="sobre-nos">
    <div class="texto-principal-quem-somos">
        <h1 class="paragrafo-destaque">
            Quem somos
        </h1>

        <p>
            Acreditamos que o sucesso vem da dedicação em entender as necessidades de cada cliente e
            transformá-las
            em soluções práticas, modernas e acessíveis. Com uma equipe comprometida e apaixonada pelo que faz,
            buscamos constantemente novas formas de otimizar processos, fortalecer parcerias e entregar
            experiências
            que realmente agregam valor.
        </p>

        <p>
            Trabalhamos com transparência, responsabilidade e foco em resultados, garantindo sempre a melhor
            qualidade
            em nossos serviços. Mais do que oferecer produtos ou sistemas, queremos construir conexões
            duradouras,
            baseadas em respeito e credibilidade.
        </p>

        <p class="paragrafo-missao">
            Nossa missão é crescer junto com nossos clientes, transformando desafios em oportunidades e ideias
            em conquistas.
        </p>
    </div>

    <article class="info-nos" id="info-objetivo">
        <div>
            <h2>Missão</h2>
            <p>Oferecer soluções digitais que fortaleçam a confiança e a comunicação dentro das comunidades</p>
        </div>
    </article>

    <article class="info-nos" id="info-visao">
        <div>
            <h2>Visão</h2>
            <p>Ser reconhecida como a principal referência nacional em plataformas digitais de gestão
                comunitária e compartilhamento de serviços, expandindo sua atuação para diferentes contextos
                residenciais</p>
        </div>
    </article>

    <article class="info-nos" id="info-valores">
        <div>
            <h2>Valores</h2>
            <p>Nossos valores são inovação, transparência, segurança, colaboração e sustentabilidade.</p>
        </div>
    </article>
</main>

<?php
include('./includes/rodape.php');
?>