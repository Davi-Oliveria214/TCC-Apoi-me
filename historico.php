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

    <div class="hi-grid">

        <!-- ══════════════════════════════════════════════════
             COLUNA 1 — CONTRATADOS
        ═══════════════════════════════════════════════════════ -->
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
$contratados = request(
	                    "contratados"
	                        . "?select=id,id_prestador,hora,dia,confirmado,observacao"
                        . ",nome_servico,nome_prestador,preco_contrato"
                        . ",servicos(imagem,descricao)"
                        . "&id_cliente=eq.$id"
                        . "&avaliar=eq.false&"
                        . "&order=dia.desc",
                    "GET"
                );

                if (!empty($contratados) && !isset($contratados['error'])):
                    foreach ($contratados as $c):
                        $hora        = date('H:i',    strtotime($c['hora']));
                        $dia         = date('d/m/Y',  strtotime($c['dia']));
                        $nomeServico = $c['nome_servico']           ?? 'Serviço removido';
                        $nomePrest   = $c['nome_prestador']         ?? 'Prestador removido';
                        $preco       = $c['preco_contrato']         ?? 0;
                        $imagem      = $c['servicos']['imagem']     ?? '';
                        $descricao   = $c['servicos']['descricao']  ?? 'Descrição não disponível';
                        $statusOk    = in_array($c['confirmado'], ['confirmado', 'concluido']);
                ?>
                        <div class="hi-card">
                            <div class="hi-card-img">
                                <?php if (!empty($imagem)): ?>
                                    <img src="<?php echo htmlspecialchars($imagem) ?>"
                                        alt="<?php echo htmlspecialchars($nomeServico) ?>">
                                <?php else: ?>
                                    <div class="hi-card-img-placeholder">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21,15 16,10 5,21" />
                                        </svg>
                                        <span>Imagem indisponível</span>
                                    </div>
                                <?php endif; ?>
                                <span class="hi-card-status <?php echo $statusOk ? 'hi-status--ok' : 'hi-status--pend' ?>">
                                    <?php echo $statusOk ? 'Confirmado' : 'Pendente' ?>
                                </span>
                            </div>

                            <div class="hi-card-info">
                                <h3><?php echo htmlspecialchars($nomeServico) ?></h3>
                                <p><?php echo htmlspecialchars($descricao) ?></p>

                                <div class="hi-card-chips">
                                    <span class="hi-chip hi-chip--prest">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                            <circle cx="12" cy="7" r="4" />
                                        </svg>
                                        <?php echo htmlspecialchars($nomePrest) ?>
                                    </span>
                                    <?php if ($preco > 0): ?>
                                        <span class="hi-chip hi-chip--preco">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="12" y1="1" x2="12" y2="23" />
                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                                            </svg>
                                            R$ <?php echo number_format($preco, 2, ',', '.') ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

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

                                <div class="hi-card-acoes">
                                    <form action="controls/mensagens.act.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="acao" value="iniciar">
                                        <input type="hidden" name="id_destinatario" value="<?= $c['id_prestador'] ?>">
                                        <button type="submit" class="hi-btn-chat-hist" title="Conversar com Prestador">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                            Chat
                                        </button>
                                    </form>
                                    <button class="hi-btn-avaliar"
                                        onclick="abrirModal('avaliar','<?php echo $c['id'] ?>')">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26 12,2" />
                                        </svg>
                                        Avaliar
                                    </button>
                                </div>
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

        <!-- ══════════════════════════════════════════════════
             COLUNA 2 — VENDAS (serviços que prestou)
        ═══════════════════════════════════════════════════════ -->
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
$vendas = request(
	                    "contratados"
	                        . "?select=id,id_cliente,hora,dia,confirmado,avaliar"
                        . ",nome_servico,nome_cliente,preco_contrato,observacao"
                        . ",servicos(imagem,nome,descricao,tipo_cobrado)"
                        . ",avaliacoes(id,nota,comentario,editado_em)"
                        . "&id_prestador=eq.$id"
                        . "&order=dia.desc",
                    "GET"
                );

                if (!empty($vendas) && !isset($vendas['error'])):
                    foreach ($vendas as $v):
                        $hora        = date('H:i',   strtotime($v['hora']));
                        $dia         = date('d/m/Y', strtotime($v['dia']));
                        $nomeServico = $v['nome_servico']        ?? 'Serviço removido';
                        $nomeCliente = $v['nome_cliente']        ?? 'Cliente removido';
                        $preco       = $v['preco_contrato']      ?? 0;
                        $imagem      = $v['servicos']['imagem']  ?? '';
                        $foiAvaliado = $v['avaliar'];
                        $nota        = $v['avaliacoes'][0]['nota'] ?? null;
                        $statusOk    = in_array($v['confirmado'], ['confirmado', 'concluido']);
                ?>
                        <div class="hi-card">
                            <div class="hi-card-img">
                                <?php if (!empty($imagem)): ?>
                                    <img src="<?php echo htmlspecialchars($imagem) ?>"
                                        alt="<?php echo htmlspecialchars($nomeServico) ?>">
                                <?php else: ?>
                                    <div class="hi-card-img-placeholder">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21,15 16,10 5,21" />
                                        </svg>
                                        <span>Imagem indisponível</span>
                                    </div>
                                <?php endif; ?>

                                <span class="hi-card-status <?php echo $statusOk ? 'hi-status--ok' : 'hi-status--pend' ?>">
                                    <?php echo $statusOk ? '✓ Confirmado' : '⏳ Pendente' ?>
                                </span>

                                <?php if ($foiAvaliado && $nota !== null): ?>
                                    <span class="hi-card-nota-badge">★ <?php echo $nota ?>/5</span>
                                <?php endif; ?>
                            </div>

                            <div class="hi-card-info">
                                <h3><?php echo htmlspecialchars($nomeServico) ?></h3>

                                <div class="hi-card-chips">
                                    <span class="hi-chip hi-chip--cliente">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                            <circle cx="12" cy="7" r="4" />
                                        </svg>
                                        <?php echo htmlspecialchars($nomeCliente) ?>
                                    </span>
                                    <?php if ($preco > 0): ?>
                                        <span class="hi-chip hi-chip--preco">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="12" y1="1" x2="12" y2="23" />
                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                                            </svg>
                                            R$ <?php echo number_format($preco, 2, ',', '.') ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

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

                                <div class="hi-card-acoes">
                                    <form action="controls/mensagens.act.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="acao" value="iniciar">
                                        <input type="hidden" name="id_destinatario" value="<?= $v['id_cliente'] ?>">
                                        <button type="submit" class="hi-btn-chat-hist" title="Conversar com Cliente">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                            Chat
                                        </button>
                                    </form>
                                    <button class="hi-btn-avaliar hi-btn-avaliar--venda"
                                        onclick="abrirModal('<?php echo $foiAvaliado ? 'ver_avaliacao_venda' : 'detalhes_venda' ?>','<?php echo $v['id'] ?>')">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                        <?php echo $foiAvaliado ? 'Ver avaliação' : 'Ver detalhes' ?>
                                    </button>
                                    <?php if ($foiAvaliado && $nota !== null): ?>
                                        <button class="hi-btn-avaliar hi-btn-avaliar--mod"
                                            onclick="abrirModal('moderar_comentario','<?php echo $v['avaliacoes'][0]['id'] ?? '' ?>')">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                                <line x1="12" y1="8" x2="12" y2="12" />
                                                <line x1="12" y1="16" x2="12.01" y2="16" />
                                            </svg>
                                            Moderar
                                        </button>
                                    <?php endif; ?>
                                </div>
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

        <!-- ══════════════════════════════════════════════════
             COLUNA 3 — AVALIADOS (já avaliados pelo cliente)
        ═══════════════════════════════════════════════════════ -->
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
                $avaliados = request(
                    "avaliacoes"
                        . "?select=id,nota,comentario,nome_servico,nome_prestador,data,horario"
                        . ",servicos(imagem)"
                        . ",contratados(preco_contrato,dia,hora)"
                        . "&id_cliente=eq.$id"
                        . "&order=id.desc",
                    "GET"
                );

                if (!empty($avaliados) && !isset($avaliados['error'])):
                    foreach ($avaliados as $a):
                        $nomeServico = $a['nome_servico']
                            ?? $a['contratados']['nome_servico']
                            ?? 'Serviço removido';

                        $nomePrest   = $a['nome_prestador']
                            ?? $a['contratados']['nome_prestador']
                            ?? 'Prestador removido';

                        $nota        = $a['nota']                            ?? 1;
                        $comentario  = $a['comentario']                      ?? '';
                        $imagem      = $a['servicos']['imagem']              ?? '';
                        $preco       = $a['contratados']['preco_contrato']   ?? 0;

                        $dataBruta   = !empty($a['data']) ? $a['data'] : ($a['contratados']['dia'] ?? '');
                        $horaBruta   = !empty($a['horario']) ? $a['horario'] : ($a['contratados']['hora'] ?? '');

                        $dia         = !empty($dataBruta) ? date('d/m/Y', strtotime($dataBruta)) : '—';
                        $hora        = !empty($horaBruta) ? date('H:i', strtotime($horaBruta)) : '—';
                ?>
                        <div class="hi-card">
                            <div class="hi-card-img">
                                <?php if (!empty($imagem)): ?>
                                    <img src="<?php echo htmlspecialchars($imagem) ?>"
                                        alt="<?php echo htmlspecialchars($nomeServico) ?>">
                                <?php else: ?>
                                    <div class="hi-card-img-placeholder">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21,15 16,10 5,21" />
                                        </svg>
                                        <span>Imagem indisponível</span>
                                    </div>
                                <?php endif; ?>

                                <span class="hi-card-nota">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <svg viewBox="0 0 24 24"
                                            fill="<?php echo $i <= $nota ? 'currentColor' : 'none' ?>"
                                            stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26 12,2" />
                                        </svg>
                                    <?php endfor; ?>
                                </span>
                            </div>

                            <div class="hi-card-info">
                                <h3><?php echo htmlspecialchars($nomeServico) ?></h3>

                                <?php if (!empty($comentario) && $comentario !== 'Nenhum comentário'): ?>
                                    <blockquote class="hi-comentario">
                                        "<?php echo htmlspecialchars($comentario) ?>"
                                    </blockquote>
                                <?php endif; ?>

                                <div class="hi-card-chips">
                                    <span class="hi-chip hi-chip--prest">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                            <circle cx="12" cy="7" r="4" />
                                        </svg>
                                        <?php echo htmlspecialchars($nomePrest) ?>
                                    </span>
                                    <?php if ($preco > 0): ?>
                                        <span class="hi-chip hi-chip--preco">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="12" y1="1" x2="12" y2="23" />
                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                                            </svg>
                                            R$ <?php echo number_format($preco, 2, ',', '.') ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

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

                                <div class="hi-card-acoes">
                                    <button class="hi-btn-avaliar hi-btn-avaliar--ver"
                                        onclick="abrirModal('ver_avaliacao','<?php echo $a['id'] ?>')">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                        Ver
                                    </button>
                                    <button class="hi-btn-avaliar hi-btn-avaliar--edit"
                                        onclick="abrirModal('editar_comentario','<?php echo $a['id'] ?>')">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                        </svg>
                                        Editar
                                    </button>
                                    <button class="hi-btn-avaliar hi-btn-avaliar--del"
                                        onclick="abrirModal('excluir_comentario','<?php echo $a['id'] ?>')">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                            <line x1="10" y1="11" x2="10" y2="17" />
                                            <line x1="14" y1="11" x2="14" y2="17" />
                                        </svg>
                                        Excluir
                                    </button>
                                </div>
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