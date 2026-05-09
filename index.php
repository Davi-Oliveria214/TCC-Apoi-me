<?php include_once './includes/head.php' ?>
<?php include_once './includes/topo.php' ?>

<!-- HERO -->
<section class="hero">
    <div class="hero-bg" id="heroBg"></div>
    <div class="hero-conteudo">
        <div class="hero-tag">Plataforma de serviços para condomínios</div>
        <h1>A solução mora<br><em>ao seu lado</em></h1>
        <p>Conectamos moradores que precisam de serviços com profissionais do seu condomínio. Rápido, seguro e de confiança.</p>
        <div class="hero-acoes">
            <a href="#" class="btn-principal">
                Ver serviços disponíveis
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14m-7-7 7 7-7 7" />
                </svg>
            </a>
            <a href="#" class="btn-secundario">Anunciar meu serviço</a>
        </div>
    </div>
</section>

<!-- FILTROS -->
<div class="filtros-section">
    <div class="filtros-inner">
        <div class="filtro-item ativo" onclick="ativarFiltro(this)">Todos</div>
        <div class="filtro-item" onclick="ativarFiltro(this)">Limpeza</div>
        <div class="filtro-item" onclick="ativarFiltro(this)">Elétrica</div>
        <div class="filtro-item" onclick="ativarFiltro(this)">Hidráulica</div>
        <div class="filtro-item" onclick="ativarFiltro(this)">Cuidados</div>
        <div class="filtro-item" onclick="ativarFiltro(this)">Pets</div>
        <div class="filtro-item" onclick="ativarFiltro(this)">Jardinagem</div>
        <div class="filtro-item" onclick="ativarFiltro(this)">Reformas</div>
        <div class="filtro-item" onclick="ativarFiltro(this)">Tecnologia</div>
    </div>
</div>

<!-- SERVIÇOS -->
<section class="servicos-section">
    <div class="secao-header">
        <div class="secao-titulo">
            <h2>Serviços disponíveis</h2>
            <p>Profissionais verificados do seu condomínio</p>
        </div>
        <a href="#" class="ver-todos">
            Ver todos
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14m-7-7 7 7-7 7" />
            </svg>
        </a>
    </div>

    <div class="servicos-grid">

        <!-- Card 1 -->
        <div class="card-servico">
            <div class="card-img-wrap">
                <img src="./img/faxineira.jpg" alt="Limpeza residencial">
                <span class="card-categoria">Limpeza</span>
                <span class="card-avaliacao">4.9</span>
            </div>
            <div class="card-corpo">
                <h3 class="card-titulo">Limpeza residencial completa</h3>
                <p class="card-desc">Serviço completo de limpeza para apartamentos e casas, incluindo todas as dependências.</p>
                <div class="card-info">
                    <div class="card-horario">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12,6 12,12 16,14" />
                        </svg>
                        08:00 – 18:00
                    </div>
                    <div class="card-preco">R$ 120 <span>/sessão</span></div>
                </div>
            </div>
            <div class="card-rodape">
                <div class="prestador">
                    <div class="prestador-avatar">MA</div>
                    <span class="prestador-nome">Maria A.</span>
                </div>
                <button class="btn-agendar">Agendar</button>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="card-servico">
            <div class="card-img-wrap">
                <img src="./img/eletricista.jpg" alt="Serviços elétricos">
                <span class="card-categoria">Elétrica</span>
                <span class="card-avaliacao">4.8</span>
            </div>
            <div class="card-corpo">
                <h3 class="card-titulo">Instalações e reparos elétricos</h3>
                <p class="card-desc">Instalação de tomadas, iluminação, disjuntores e manutenção elétrica em geral.</p>
                <div class="card-info">
                    <div class="card-horario">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12,6 12,12 16,14" />
                        </svg>
                        07:00 – 17:00
                    </div>
                    <div class="card-preco">R$ 80 <span>/hora</span></div>
                </div>
            </div>
            <div class="card-rodape">
                <div class="prestador">
                    <div class="prestador-avatar">JS</div>
                    <span class="prestador-nome">João S.</span>
                </div>
                <button class="btn-agendar">Agendar</button>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="card-servico">
            <div class="card-img-wrap">
                <img src="./img/cuidador-de-cachorro.jpg" alt="Cuidador de pets">
                <span class="card-categoria">Pets</span>
                <span class="card-avaliacao">5.0</span>
            </div>
            <div class="card-corpo">
                <h3 class="card-titulo">Cuidador e passeador de pets</h3>
                <p class="card-desc">Passeios diários, cuidados especiais e acompanhamento com muito carinho e responsabilidade.</p>
                <div class="card-info">
                    <div class="card-horario">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12,6 12,12 16,14" />
                        </svg>
                        06:00 – 20:00
                    </div>
                    <div class="card-preco">R$ 50 <span>/passeio</span></div>
                </div>
            </div>
            <div class="card-rodape">
                <div class="prestador">
                    <div class="prestador-avatar">CL</div>
                    <span class="prestador-nome">Carla L.</span>
                </div>
                <button class="btn-agendar">Agendar</button>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="card-servico">
            <div class="card-img-wrap">
                <img src="./img/baba.jpg" alt="Babá">
                <span class="card-categoria">Cuidados</span>
                <span class="card-avaliacao">4.7</span>
            </div>
            <div class="card-corpo">
                <h3 class="card-titulo">Babá experiente e atenciosa</h3>
                <p class="card-desc">Cuidados com crianças de todas as idades, com experiência e muito carinho.</p>
                <div class="card-info">
                    <div class="card-horario">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12,6 12,12 16,14" />
                        </svg>
                        08:00 – 22:00
                    </div>
                    <div class="card-preco">R$ 70 <span>/hora</span></div>
                </div>
            </div>
            <div class="card-rodape">
                <div class="prestador">
                    <div class="prestador-avatar">PM</div>
                    <span class="prestador-nome">Paula M.</span>
                </div>
                <button class="btn-agendar">Agendar</button>
            </div>
        </div>

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
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
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
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
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
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
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
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
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
            <?php if (empty($id)): ?><a href="#" class="btn-principal">Criar minha conta</a><?php endif; ?>
            <a href="#" class="btn-secundario">Anunciar serviço</a>
        </div>
    </div>
</section>

<?php include_once './includes/rodape.php'; ?>