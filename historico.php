<?php
require_once './includes/funcoes.php';
exigirLogin();

include('./includes/head.php');
include('./includes/topo.php');
?>

<link rel="stylesheet" href="./css/historico.css">

<main class="pag-historico">

    <!-- ===== HERO ===== -->
    <div class="hi-hero">
        <div class="hi-hero-inner">
            <div>
                <span class="hi-subtag">Sua atividade</span>
                <h1>Histórico</h1>
                <p>Acompanhe serviços contratados, vendas realizadas e avaliações feitas</p>
            </div>
            <a href="./servicos.php" class="hi-btn-voltar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5m7-7-7 7 7 7" />
                </svg>
                Ver serviços
            </a>
        </div>
    </div>

    <!-- ===== GRID DE COLUNAS ===== -->
    <div class="hi-grid">

        <!-- ===== CONTRATADOS ===== -->
        <section class="hi-coluna">
            <div class="hi-coluna-header">
                <div class="hi-col-icone hi-col-icone--azul">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                </div>
                <div>
                    <h2>Contratados</h2>
                    <p>Serviços que você contratou</p>
                </div>
            </div>

            <div class="hi-lista">
                <?php
                $servicos = request(
                    "contratados?select=id,hora,dia,confirmado,servicos(id,nome,descricao,imagem)&id_cliente=eq.$id&avaliar=eq.false",
                    "GET"
                );

                if (!empty($servicos) && !isset($servicos['error'])):
                    foreach ($servicos as $s):
                        $hora = date('H:i', strtotime($s['hora']));
                        $dia  = date('d/m/Y', strtotime($s['dia']));
                ?>
                        <div class="hi-card">
                            <div class="hi-card-img">
                                <img src="<?php echo htmlspecialchars($s['servicos']['imagem']) ?>" alt="<?php echo htmlspecialchars($s['servicos']['nome']) ?>">
                                <span class="hi-card-status <?php echo $s['confirmado'] ? 'hi-status--ok' : 'hi-status--pend' ?>">
                                    <?php echo $s['confirmado'] ? '✓ Confirmado' : '⏳ Pendente' ?>
                                </span>
                            </div>
                            <div class="hi-card-info">
                                <h3><?php echo htmlspecialchars($s['servicos']['nome']) ?></h3>
                                <p><?php echo htmlspecialchars($s['servicos']['descricao']) ?></p>
                                <div class="hi-card-meta">
                                    <span>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="4" width="18" height="18" rx="2" />
                                            <line x1="16" y1="2" x2="16" y2="6" />
                                            <line x1="8" y1="2" x2="8" y2="6" />
                                            <line x1="3" y1="10" x2="21" y2="10" />
                                        </svg>
                                        <?php echo $dia ?>
                                    </span>
                                    <span>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12,6 12,12 16,14" />
                                        </svg>
                                        <?php echo $hora ?>
                                    </span>
                                </div>
                                <button class="hi-btn-avaliar"
                                    onclick="abrirAvaliar('<?php echo $s['servicos']['id'] ?>', '<?php echo htmlspecialchars($s['servicos']['nome']) ?>', '<?php echo $s['servicos']['imagem'] ?>', '<?php echo $s['dia'] ?>', '<?php echo $s['hora'] ?>', '<?php echo $s['confirmado'] ?>', '<?php echo $s['id'] ?>')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26 12,2" />
                                    </svg>
                                    Avaliar serviço
                                </button>
                            </div>
                        </div>
                    <?php
                    endforeach;
                else:
                    ?>
                    <div class="hi-vazio">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <path d="M16 10a4 4 0 0 1-8 0" />
                        </svg>
                        <p>Nenhum serviço contratado ainda</p>
                        <a href="./servicos.php">Explorar serviços</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- ===== VENDAS ===== -->
        <section class="hi-coluna">
            <div class="hi-coluna-header">
                <div class="hi-col-icone hi-col-icone--dourado">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23" />
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                </div>
                <div>
                    <h2>Vendas</h2>
                    <p>Serviços que você realizou</p>
                </div>
            </div>

            <div class="hi-lista">
                <?php
                $servicos = request(
                    "contratados?select=id,hora,dia,confirmado,servicos(nome,descricao,imagem)&id_prestador=eq.$id",
                    "GET"
                );

                if (!empty($servicos) && !isset($servicos['error'])):
                    foreach ($servicos as $s):
                        $hora = date('H:i', strtotime($s['hora']));
                        $dia  = date('d/m/Y', strtotime($s['dia']));
                ?>
                        <div class="hi-card">
                            <div class="hi-card-img">
                                <img src="<?php echo htmlspecialchars($s['servicos']['imagem']) ?>" alt="<?php echo htmlspecialchars($s['servicos']['nome']) ?>">
                                <span class="hi-card-status <?php echo $s['confirmado'] ? 'hi-status--ok' : 'hi-status--pend' ?>">
                                    <?php echo $s['confirmado'] ? '✓ Confirmado' : '⏳ Pendente' ?>
                                </span>
                            </div>
                            <div class="hi-card-info">
                                <h3><?php echo htmlspecialchars($s['servicos']['nome']) ?></h3>
                                <p><?php echo htmlspecialchars($s['servicos']['descricao']) ?></p>
                                <div class="hi-card-meta">
                                    <span>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="4" width="18" height="18" rx="2" />
                                            <line x1="16" y1="2" x2="16" y2="6" />
                                            <line x1="8" y1="2" x2="8" y2="6" />
                                            <line x1="3" y1="10" x2="21" y2="10" />
                                        </svg>
                                        <?php echo $dia ?>
                                    </span>
                                    <span>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12,6 12,12 16,14" />
                                        </svg>
                                        <?php echo $hora ?>
                                    </span>
                                </div>
                                <button class="hi-btn-avaliar hi-btn-avaliar--venda"
                                    onclick="abrirAvaliar('<?php echo $s['id'] ?>', '<?php echo htmlspecialchars($s['servicos']['nome']) ?>', '<?php echo $s['servicos']['imagem'] ?>', '<?php echo $s['dia'] ?>', '<?php echo $s['hora'] ?>', '<?php echo $s['confirmado'] ?>', '<?php echo $s['id'] ?>')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    Ver detalhes
                                </button>
                            </div>
                        </div>
                    <?php
                    endforeach;
                else:
                    ?>
                    <div class="hi-vazio">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23" />
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                        </svg>
                        <p>Nenhuma venda ainda</p>
                        <a href="./anunciar.php">Anunciar serviço</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- ===== AVALIADOS ===== -->
        <section class="hi-coluna">
            <div class="hi-coluna-header">
                <div class="hi-col-icone hi-col-icone--estrela">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26 12,2" />
                    </svg>
                </div>
                <div>
                    <h2>Avaliados</h2>
                    <p>Serviços que você já avaliou</p>
                </div>
            </div>

            <div class="hi-lista">
                <?php
                $servicos = request(
                    "contratados?select=id,hora,dia,confirmado,servicos(nome,descricao,imagem),avaliacao(nota,comentario)&id_cliente=eq.$id&avaliar=eq.true",
                    "GET"
                );

                if (!empty($servicos) && !isset($servicos['error'])):
                    foreach ($servicos as $s):
                        $hora  = date('H:i', strtotime($s['hora']));
                        $dia   = date('d/m/Y', strtotime($s['dia']));
                        $nota  = $s['avaliacao'][0]['nota'] ?? 0;
                ?>
                        <div class="hi-card">
                            <div class="hi-card-img">
                                <img src="<?php echo htmlspecialchars($s['servicos']['imagem']) ?>" alt="<?php echo htmlspecialchars($s['servicos']['nome']) ?>">
                                <span class="hi-card-nota">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <svg viewBox="0 0 24 24" fill="<?php echo $i <= $nota ? 'currentColor' : 'none' ?>" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26 12,2" />
                                        </svg>
                                    <?php endfor; ?>
                                </span>
                            </div>
                            <div class="hi-card-info">
                                <h3><?php echo htmlspecialchars($s['servicos']['nome']) ?></h3>
                                <p><?php echo htmlspecialchars($s['servicos']['descricao']) ?></p>
                                <?php if (!empty($s['avaliacao'][0]['comentario'])): ?>
                                    <blockquote class="hi-comentario">
                                        "<?php echo htmlspecialchars($s['avaliacao'][0]['comentario']) ?>"
                                    </blockquote>
                                <?php endif; ?>
                                <div class="hi-card-meta">
                                    <span>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="4" width="18" height="18" rx="2" />
                                            <line x1="16" y1="2" x2="16" y2="6" />
                                            <line x1="8" y1="2" x2="8" y2="6" />
                                            <line x1="3" y1="10" x2="21" y2="10" />
                                        </svg>
                                        <?php echo $dia ?>
                                    </span>
                                </div>
                                <button class="hi-btn-avaliar hi-btn-avaliar--ver"
                                    onclick="verAvaliacao('<?php echo htmlspecialchars($s['servicos']['nome']) ?>', '<?php echo $s['servicos']['imagem'] ?>', '<?php echo addslashes($s['avaliacao'][0]['comentario'] ?? '') ?>', '<?php echo $nota ?>', '<?php echo $s['id'] ?>')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    Ver avaliação
                                </button>
                            </div>
                        </div>
                    <?php
                    endforeach;
                else:
                    ?>
                    <div class="hi-vazio">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26 12,2" />
                        </svg>
                        <p>Nenhum serviço avaliado ainda</p>
                        <a href="./servicos.php">Contratar um serviço</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>

    </div>
</main>

<?php include("./includes/rodape.php"); ?>