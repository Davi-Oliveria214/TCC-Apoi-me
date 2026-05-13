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
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round">
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
                    <li class="filtro-item js-filtro" onclick="filtro(this, 'servicos', <?php echo $cate['id'] ?>)">
                        <?php echo $cate['nome'] ?></li>
            <?php
                endforeach;
            endif; ?>
        </ul>
    </div>

    <div class="sv-layout">
        <aside class="sv-aside">

            <div class="sv-quadro">
                <div class="sv-quadro-header">
                    <img src="./icon/sino.png" alt="">
                    <h2>Quadro de Avisos</h2>
                </div>
                <div class="sv-quadro-corpo">
                    <?php
                    $avisos = request("avisos?codigo=eq.{$_SESSION['codigo']}");
                    if (!empty($avisos) && !isset($avisos['error'])):
                        foreach ($avisos as $aviso):
                            $cridado = date("d/m/Y", strtotime($aviso['criado_em']));
                            $data    = date("d/m/Y", strtotime($aviso['data_evento']));
                    ?>
                            <div class="sv-aviso-card">
                                <div class="sv-aviso-titulo">
                                    <div class="sv-aviso-icone">
                                        <img src="./icon/alerta.png" alt="">
                                    </div>
                                    <div>
                                        <strong><?php echo htmlspecialchars($aviso['titulo']) ?></strong>
                                        <span>Por: <?php echo htmlspecialchars($aviso['autor']) ?></span>
                                    </div>
                                </div>
                                <div class="sv-aviso-datas">
                                    <span>Evento: <?php echo $data ?></span>
                                    <span>Postado: <?php echo $cridado ?></span>
                                </div>
                                <p><?php echo htmlspecialchars($aviso['mensagem']) ?></p>
                            </div>
                        <?php
                        endforeach;
                    else:
                        ?>
                        <div class='aviso-vazio'>Nenhum aviso do condomínio</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Serviços reservados -->
            <div class="sv-quadro">
                <div class="sv-quadro-header">
                    <img src="./icon/calendario.png" alt="">
                    <h2>Reservados</h2>
                </div>
                <div class="sv-quadro-corpo sv-reservados-lista">
                    <?php
                    $endpoint = "contratados?id_cliente=eq.$id&select=*,servicos(nome,descricao,imagem,id_prestador,nota_geral)status=eq.true&order=dia.desc";
                    $sql = request($endpoint, "GET");

                    if (!empty($sql) && !isset($sql['error'])):
                        foreach ($sql as $res):
                            $horario    = date('H:i', strtotime($res['hora']));
                            $dataRes    = date('d/m/Y', strtotime($res['dia']));
                            $status     = $res['confirmado'];
                            $idContrato = $res['id'];
                            $nomeServ   = $res['servicos']['nome'];
                            $descServ   = $res['servicos']['descricao'];
                            $imgRes     = $res['servicos']['imagem'];
                    ?>
                            <div class="sv-reservado-card">
                                <div class="sv-res-img">
                                    <img src="<?php echo htmlspecialchars($imgRes) ?>"
                                        alt="<?php echo htmlspecialchars($nomeServ) ?>">
                                    <span class="sv-res-status <?php echo $status ? 'confirmado' : 'pendente' ?>">
                                        <?php echo $status ? 'Confirmado' : 'Pendente' ?>
                                    </span>
                                </div>
                                <div class="sv-res-info">
                                    <h3><?php echo htmlspecialchars($nomeServ) ?></h3>
                                    <div class="sv-res-datas">
                                        <span>
                                            <img src="./icon/calendario.png" alt="">
                                            <?php echo $dataRes ?>
                                        </span>
                                        <span>
                                            <img src="./icon/relogio.png" alt="">
                                            <?php echo $horario ?>
                                        </span>
                                    </div>
                                    <div class="sv-res-acoes">
                                        <button class="sv-btn-sm sv-btn-detalhe"
                                            onclick="abrirModal('detalhes','<?php echo  $res['id'] ?>')">
                                            Detalhes
                                        </button>
                                        <a href="./mensagens.php" class="sv-btn-sm sv-btn-chat">Chat</a>
                                        <button class="sv-btn-sm sv-btn-cancelar"
                                            onclick="abrirModal('cancelar','<?php echo  $res['id'] ?>')">
                                            Cancelar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endforeach;
                    else:
                        ?>
                        <div class="sv-vazio">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                            <p>Nenhum serviço reservado</p>
                        </div>
                    <?php endif; ?>
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
                                if (!empty($id) && $_SESSION['id'] != $servico['id_prestador']):
                                ?>
                                    <button class="btn-agendar"
                                        onclick="abrirModal('agendar','<?php echo $servico['id'] ?>')">Agendar</button>
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
        </section>

    </div>
</main>

<?php include "./includes/rodape.php"; ?>