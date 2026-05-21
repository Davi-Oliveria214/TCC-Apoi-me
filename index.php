<?php include_once './includes/head.php' ?>
<?php include_once './includes/topo.php' ?>

<!-- HERO -->
<section class="hero">
    <div class="hero-bg" id="heroBg"></div>
    <div class="hero-conteudo">
        <div class="hero-tag">Plataforma de serviços para condomínios</div>
        <h1>A solução mora<br><em>ao seu lado</em></h1>
        <p>Conectamos moradores que precisam de serviços com profissionais do seu condomínio. Rápido, seguro e de
            confiança.</p>
        <div class="hero-acoes">
            <a href="<?php echo empty($_SESSION['id']) ? './util/setAviso.php' : './servicos.php' ?>"
                class="btn-principal">
                Ver serviços disponíveis
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14m-7-7 7 7-7 7" />
                </svg>
            </a>
            <a href="<?php echo empty($_SESSION['id']) ? './util/setAviso.php' : './anunciar.php' ?>"
                class="btn-secundario">Anunciar meu serviço</a>
        </div>
    </div>
</section>

<!-- FILTROS -->
<div class="filtros-section">
    <?php $categorias = request("categorias?select=*&order=nome.asc", "GET"); ?>
    <ul class="filtros-inner">
        <li class="filtro-item js-filtro ativo" onclick="filtro(this, 'servicos', 0)">Todos</li>
        <?php if (!empty($categorias) && !isset($categorias['code'])):
            foreach ($categorias as $cate): ?>
                <li class="filtro-item js-filtro" onclick="filtro(this, 'servicos', <?php echo $cate['id'] ?>)">
                    <?php echo $cate['nome'] ?></li>
        <?php
            endforeach;
        endif; ?>
    </ul>
</div>

<!-- SERVIÇOS -->
<section class="servicos-section">
    <div class="secao-header">
        <div class="secao-titulo">
            <h2>Serviços disponíveis</h2>
            <p>Profissionais verificados do seu condomínio</p>
        </div>
        <a href="<?php echo empty($_SESSION['id']) ? './util/setAviso.php' : './servicos.php' ?>" class="ver-todos">
            Ver todos
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14m-7-7 7 7-7 7" />
            </svg>
        </a>
    </div>

    <div class="carrossel-container">
        <button class="carrossel-btn prev" onclick="scrollCarrossel(-1)">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6" />
            </svg>
        </button>
        <div class="servicos-grid local-filtro carrossel-track">
            <?php
            if (empty($_SESSION['codigo'])) {
                $servicos = request("servicos?status=eq.true&select=*,categorias(nome),usuarios(nome)&order=criado.desc&limit=10", "GET");
            } else {
                $servicos = request("servicos?status=eq.true&codigo=eq.{$_SESSION['codigo']}&select=*,categorias(nome),usuarios(nome)&order=criado.desc&limit=10", "GET");
            }

            if (!empty($servicos) && !isset($servicos['error'])) :
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
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12,6 12,12 16,14" />
                                    </svg>
                                    <?php echo $horaInicio ?> – <?php echo $horaFim ?>
                                </div>
                                <div class="card-preco">R$<?php echo $servico['preco_servico'] ?><span> /
                                        <?php echo $servico['tipo_cobrado'] ?></span></div>
                            </div>
                        </div>
                        <div class="card-rodape">
                            <div class="prestador">
                                <div class="prestador-avatar"><?php echo substr($servico['usuarios']['nome'], 0, 1) ?></div>
                                <span class="prestador-nome"><?php echo $servico['usuarios']['nome'] ?></span>
                            </div>
                            <?php
                            if (empty($id) || $_SESSION['id'] != $servico['id_prestador']):
                            ?>
                                <button class="btn-agendar"
                                    onclick="<?php echo !empty($_SESSION['id']) ? "abrirModal('agendar','{$servico['id']}')" : "window.location.href='./util/setAviso.php'" ?>">Agendar</button>
                            <?php
                            endif;
                            ?>
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
        <button class="carrossel-btn next" onclick="scrollCarrossel(1)">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m9 18 6-6-9-6" />
            </svg>
        </button>
    </div>
</section>

<!-- COMO FUNCIONA -->
<section class="como-section">
    <div class="como-inner">
        <div class="como-header">
            <p class="subtitulo">Simples e rápido</p>
            <h2>Como funciona</h2>
            <p>Encontre o profissional ideal em poucos passos, sem complicação.</p>
        </div>

        <div class="passos-grid">
            <div class="passo">
                <span class="passo-num">01</span>
                <div class="passo-icone">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                </div>
                <h3>Busque o serviço</h3>
                <p>Pesquise por categoria ou use a busca para encontrar exatamente o que precisa no seu condomínio.</p>
            </div>

            <div class="passo">
                <span class="passo-num">02</span>
                <div class="passo-icone">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                </div>
                <h3>Conheça o profissional</h3>
                <p>Veja o perfil, avaliações de outros moradores e histórico de serviços antes de decidir.</p>
            </div>

            <div class="passo">
                <span class="passo-num">03</span>
                <div class="passo-icone">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                </div>
                <h3>Agende com facilidade</h3>
                <p>Escolha o dia e horário que preferir. Confirmação instantânea diretamente na plataforma.</p>
            </div>

            <div class="passo">
                <span class="passo-num">04</span>
                <div class="passo-icone">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round">
                        <polyline points="20,6 9,17 4,12" />
                    </svg>
                </div>
                <h3>Avalie e colabore</h3>
                <p>Após o serviço, deixe sua avaliação e ajude a comunidade a encontrar os melhores profissionais.</p>
            </div>
        </div>
    </div>
</section>

<!-- PARCEIROS -->
<section class="parceiros-section">
    <div class="secao-header">
        <div class="secao-titulo">
            <h2>Empresas parceiras</h2>
            <p>Marcas que confiam na nossa plataforma</p>
        </div>
    </div>

    <div class="parceiros-grid">
        <div class="card-parceiro">
            <div class="parceiro-img">
                <img src="./img/condomino.png" alt="Empresa parceira">
            </div>
            <div class="parceiro-corpo">
                <p class="parceiro-tag">Gestão condominial</p>
                <h3>Empresa Parceira</h3>
                <p>Soluções especializadas para administração e gestão do seu condomínio.</p>
            </div>
        </div>

        <div class="card-parceiro">
            <div class="parceiro-img">
                <img src="./img/condomino.png" alt="Empresa parceira">
            </div>
            <div class="parceiro-corpo">
                <p class="parceiro-tag">Segurança</p>
                <h3>Empresa Parceira</h3>
                <p>Monitoramento e segurança 24h para sua tranquilidade e da sua família.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="cta-inner">
        <p class="subtitulo">Comece agora</p>
        <h2>Faça parte da comunidade</h2>
        <p>Cadastre-se gratuitamente e descubra como é fácil contratar ou oferecer serviços no seu condomínio.</p>
        <div class="cta-acoes">
            <?php if (empty($id)): ?><a href="./cadastro.php" class="btn-principal">Criar minha conta</a><?php endif; ?>
            <a href="<?php echo empty($_SESSION['id']) ? './util/setAviso.php' : './anunciar.php' ?>"
                class="btn-secundario">Anunciar serviço</a>
        </div>
    </div>
</section>

<?php include_once './includes/rodape.php'; ?>