<?php
require("./includes/conexao.php");
include "./includes/cabecalho.php";
?>

<main class="principal">
    <section class="avisos-eventos">
        <div class="reservados quadro">
            <h2>Contratados</h2>
            <div class="box">
                <?php
                // $sql = mysqli_query($con, )
                ?>
                <article class="card-servicos">
                    <img src="./img/baba.jpg" alt=Eletricista>
                    <div class="card-conteudo">
                        <div class="card-sobre">
                            <h3>Baba</h3>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam vel adipisci, fuga
                                eius unde libero quos nisi aut, sunt nihil, dolore voluptatem provident iusto ipsam
                                aperiam atque impedit dignissimos eligendi!</p>
                            <div class="horarios">
                                <data class="data-servico">Marccado: 01/08/2026</data>
                                <p>Horário: 14:00</p>
                            </div>
                        </div>
                    </div>
                    <div class="box-btn">
                        <a href="" class="btn remarcar">Remarcar</a>
                        <a href="" class="btn cancelar">Cancelar</a>
                    </div>
                </article>
            </div>
        </div>
        <div class="avisos quadro">
            <h2>Quadro de Avisos</h2>
            <div class="box">
                <article class="card-avisos">
                    <div class="titulo-avisos">
                        <img src="./img/icone.png" alt="">
                        <div>
                            <h2>Titulo de teste</h2>
                            <p>Data </p>
                        </div>
                    </div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea nam facere repudiandae dolor
                        dolore, laudantium quo error molestiae totam pariatur recusandae quidem illum natus soluta
                        perspiciatis repellat? Quod, at impedit.</p>
                    <img src="./img/a-mostra.jpg" alt="" class="img-aviso">
                </article>
                <article class="card-avisos">
                    <div class="titulo-avisos">
                        <img src="./img/icone.png" alt="">
                        <div>
                            <h2>Titulo de teste</h2>
                            <p>Data </p>
                        </div>
                    </div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea nam facere repudiandae dolor
                        dolore, laudantium quo error molestiae totam pariatur recusandae quidem illum natus soluta
                        perspiciatis repellat? Quod, at impedit.</p>
                    <img src="./img/a-mostra.jpg" alt="" class="img-aviso">
                </article>
            </div>
        </div>
    </section>

    <section class="servicos">
        <h2>Serviços</h2>
        <section class="sessao-servicos">
            <article class="card-servicos">
                <img src="./img/eletricista.jpg" alt="Eletricista">
                <div class="card-conteudo">
                    <div class="card-sobre">
                        <h3>Eletricista</h3>
                        <p>Instalações residenciais, manutenção de quadros e reparos emergenciais.</p>
                    </div>
                    <data value="" class="data-servico">Disponível: 15/12 a 20/12</data>
                    <button class="btn botao-ver-mais" onclick="modelo('abrir')">Ver detalhes</button>
                </div>
            </article>
            <article class="card-servicos">
                <img src="./img/eletricista.jpg" alt="Eletricista">
                <div class="card-conteudo">
                    <div class="card-sobre">
                        <h3>Eletricista</h3>
                        <p>Instalações residenciais, manutenção de quadros e reparos emergenciais.</p>
                    </div>
                    <data value="" class="data-servico">Disponível: 15/12 a 20/12</data>
                    <button class="btn botao-ver-mais" onclick="modelo('abrir')">Ver detalhes</button>
                </div>
            </article>
            <article class="card-servicos">
                <img src="./img/eletricista.jpg" alt="Eletricista">
                <div class="card-conteudo">
                    <div class="card-sobre">
                        <h3>Eletricista</h3>
                        <p>Instalações residenciais, manutenção de quadros e reparos emergenciais.</p>
                    </div>
                    <data value="" class="data-servico">Disponível: 15/12 a 20/12</data>
                    <button class="btn botao-ver-mais" onclick="modelo('abrir')">Ver detalhes</button>
                </div>
            </article>
            <article class="card-servicos">
                <img src="./img/eletricista.jpg" alt="Eletricista">
                <div class="card-conteudo">
                    <div class="card-sobre">
                        <h3>Eletricista</h3>
                        <p>Instalações residenciais, manutenção de quadros e reparos emergenciais.</p>
                    </div>
                    <data value="" class="data-servico">Disponível: 15/12 a 20/12</data>
                    <button class="btn botao-ver-mais" onclick="modelo('abrir')">Ver detalhes</button>
                </div>
            </article>
            <article class="card-servicos">
                <img src="./img/eletricista.jpg" alt="Eletricista">
                <div class="card-conteudo">
                    <div class="card-sobre">
                        <h3>Eletricista</h3>
                        <p>Instalações residenciais, manutenção de quadros e reparos emergenciais.</p>
                    </div>
                    <data value="" class="data-servico">Disponível: 15/12 a 20/12</data>
                    <button class="btn botao-ver-mais" onclick="modelo('abrir')">Ver detalhes</button>
                </div>
            </article>
            <article class="card-servicos">
                <img src="./img/eletricista.jpg" alt="Eletricista">
                <div class="card-conteudo">
                    <div class="card-sobre">
                        <h3>Eletricista</h3>
                        <p>Instalações residenciais, manutenção de quadros e reparos emergenciais.</p>
                    </div>
                    <data value="" class="data-servico">Disponível: 15/12 a 20/12</data>
                    <button class="btn botao-ver-mais" onclick="modelo('abrir')">Ver detalhes</button>
                </div>
            </article>
            <article class="card-servicos">
                <img src="./img/eletricista.jpg" alt="Eletricista">
                <div class="card-conteudo">
                    <div class="card-sobre">
                        <h3>Eletricista</h3>
                        <p>Instalações residenciais, manutenção de quadros e reparos emergenciais.</p>
                    </div>
                    <data value="" class="data-servico">Disponível: 15/12 a 20/12</data>
                    <button class="btn botao-ver-mais" onclick="modelo('abrir')">Ver detalhes</button>
                </div>
            </article>
        </section>
    </section>
</main>

<?php
include "./includes/rodape.php";
?>