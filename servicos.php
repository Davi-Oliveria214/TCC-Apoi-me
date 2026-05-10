<?php
require_once './includes/funcoes.php';
exigirLogin();

include('./includes/head.php');
include('./includes/topo.php');
?>

<main class="pag-servicos">
    <div class="sv-hero">
        <div class="sv-hero-inner">
            <div>
                <span class="sv-subtag">Seu condomínio</span>
                <h1>Serviços disponíveis</h1>
                <p>Profissionais verificados prontos para te atender</p>
            </div>
            <a href="./anunciar.php" class="btn-anunciar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Anunciar meu serviço
            </a>
        </div>
    </div>

    <!-- ===== FILTROS ===== -->
    <div class="filtros-section">
        <?php $categorias = request("categorias?select=*&order=nome.asc", "GET"); ?>
        <ul class="filtros-inner">
            <li class="filtro-item js-filtro ativo" onclick="filtro(this, 'servicos', 0)">Todos</li>
            <?php if (!empty($categorias) && !isset($categorias['code'])):
                foreach ($categorias as $cate): ?>
                    <li class="filtro-item js-filtro" onclick="filtro(this, 'servicos', <?php echo $cate['id'] ?>)"><?php echo $cate['nome'] ?></li>
            <?php
                endforeach;
            endif; ?>
        </ul>
    </div>

    <div class="sv-layout">
        <aside class="sv-aside">

            <div class="sv-quadro">
                <div class="sv-quadro-header">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                    </svg>
                    <h2>Quadro de Avisos</h2>
                </div>
                <div class="sv-quadro-corpo">
                    <?php $avisos = request("avisos?codigo=eq.{$_SESSION['codigo']}"); ?>
                </div>
            </div>

            <!-- Serviços reservados -->
            <div class="sv-quadro">
                <div class="sv-quadro-header">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    <h2>Reservados</h2>
                </div>
                <div class="sv-quadro-corpo sv-reservados-lista">
                    <?php
                    $endpoint = "contratados?id_cliente=eq.$id&select=*,servicos(nome,descricao,imagem,id_prestador)status=eq.true&order=dia.desc";
                    $sql = request($endpoint, "GET");

                    if (!empty($sql) && !isset($sql['error'])) :
                        foreach ($sql as $servico) :
                            $horaInicio = date('H:i', strtotime($servico['hora_inicio']));
                            $horaFim = date('H:i', strtotime($servico['hora_fim']));
                            $duracao = date('H:i', strtotime($servico['duracao']));
                            $imagem = $servico['imagem'];
                    ?>
                            <div class="card-servico">
                                <div class="card-img-wrap">
                                    <img src="<?php echo $imagem ?>" alt="<?php echo $servico['nome'] ?>">
                                    <span class="card-categoria"><?php echo $servico['categorias']['nome'] ?></span>
                                    <span class="card-avaliacao"><?php echo $servico['nota_geral'] ?></span>
                                </div>
                                <div class="card-corpo">
                                    <h3 class="card-titulo"><?php echo $servico['nome'] ?></h3>
                                    <p class="card-desc"><?php echo $servico['descricao'] ?></p>
                                    <div class="card-info">
                                        <div class="card-horario">
                                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10" />
                                                <polyline points="12,6 12,12 16,14" />
                                            </svg>
                                            <?php echo $horaInicio ?> – <?php echo $horaFim ?>
                                        </div>
                                        <div class="card-preco">R$<?php echo $servico['preco_servico'] ?><span> / <?php echo $servico['tipo_cobrado'] ?></span></div>
                                    </div>
                                </div>
                                <div class="card-rodape">
                                    <div class="prestador">
                                        <div class="prestador-avatar"><?php echo substr($servico['usuarios']['nome'], 0, 1) ?></div>
                                        <span class="prestador-nome"><?php echo $servico['usuarios']['nome'] ?></span>
                                    </div>
                                    <button class="btn-agendar">Agendar</button>
                                </div>
                            </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>

        </aside>

        <section class="sv-grid-wrap">
            <div class="sv-grid-header">
                <h2>Serviços do seu condomínio</h2>
                <span class="sv-count" id="sv-count"></span>
            </div>

            <div class="sv-grid local-filtro" id="sv-grid">
                <?php
                $servicos = request("servicos?status=eq.true&select=*,categorias(nome),usuarios(nome)&order=criado.desc&limit=10", "GET");

                if (!empty($servicos) && !isset($servicos['error'])) :
                    shuffle($servicos);
                    foreach ($servicos as $servico) :
                        $horaInicio = date('H:i', strtotime($servico['hora_inicio']));
                        $horaFim = date('H:i', strtotime($servico['hora_fim']));
                        $duracao = date('H:i', strtotime($servico['duracao']));
                        $imagem = $servico['imagem'];
                ?>
                        <div class="card-servico">
                            <div class="card-img-wrap">
                                <img src="<?php echo $imagem ?>" alt="<?php echo $servico['nome'] ?>">
                                <span class="card-categoria"><?php echo $servico['categorias']['nome'] ?></span>
                                <span class="card-avaliacao"><?php echo $servico['nota_geral'] ?></span>
                            </div>
                            <div class="card-corpo">
                                <h3 class="card-titulo"><?php echo $servico['nome'] ?></h3>
                                <p class="card-desc"><?php echo $servico['descricao'] ?></p>
                                <div class="card-info">
                                    <div class="card-horario">
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12,6 12,12 16,14" />
                                        </svg>
                                        <?php echo $horaInicio ?> – <?php echo $horaFim ?>
                                    </div>
                                    <div class="card-preco">R$<?php echo $servico['preco_servico'] ?><span> / <?php echo $servico['tipo_cobrado'] ?></span></div>
                                </div>
                            </div>
                            <div class="card-rodape">
                                <div class="prestador">
                                    <div class="prestador-avatar"><?php echo substr($servico['usuarios']['nome'], 0, 1) ?></div>
                                    <span class="prestador-nome"><?php echo $servico['usuarios']['nome'] ?></span>
                                </div>
                                <button class="btn-agendar">Agendar</button>
                            </div>
                        </div>
                    <?php
                    endforeach;
                else :
                    ?>
                    <div class='aviso-vazio'>Nenhum serviço encontrado</div>
                <?php
                endif;
                ?>
            </div>
        </section>

    </div>
</main>

<?php include "./includes/rodape.php"; ?>