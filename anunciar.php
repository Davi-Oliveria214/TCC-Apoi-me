<?php
require_once './includes/funcoes.php';
exigirLogin();

include('./includes/head.php');
include('./includes/topo.php');
?>

<main class="pag-anunciar">
    <div class="an-hero">
        <div class="an-hero-inner">
            <div>
                <span class="an-subtag">Área do prestador</span>
                <h1>Meus Serviços</h1>
                <p>Gerencie os serviços que você oferece ao condomínio</p>
            </div>
            <button class="an-btn-novo" onclick="abrirModal('novo')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Novo Serviço
            </button>
        </div>
    </div>

    <?php
    $res = request("servicos?id_prestador=eq.{$id}&select=count");
    $total = $res[0]['count'] ?? 0;

    $resAtivos = request("servicos?id_prestador=eq.{$id}&status=eq.true&select=count");
    $ativos = $resAtivos[0]['count'] ?? 0;

    $pausados = $total - $ativos;
    ?>
    <div class="an-stats">
        <div class="an-stat">
            <div class="an-stat-icone">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                    <polyline points="14,2 14,8 20,8" />
                    <line x1="16" y1="13" x2="8" y2="13" />
                    <line x1="16" y1="17" x2="8" y2="17" />
                    <polyline points="10,9 9,9 8,9" />
                </svg>
            </div>
            <div class="an-stat-txt">
                <span class="an-stat-num"><?php echo $total ?></span>
                <span class="an-stat-label">Total de serviços</span>
            </div>
        </div>
        <div class="an-stat">
            <div class="an-stat-icone an-stat-icone--ativo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20,6 9,17 4,12" />
                </svg>
            </div>
            <div class="an-stat-txt">
                <span class="an-stat-num"><?php echo $ativos ?></span>
                <span class="an-stat-label">Ativos</span>
            </div>
        </div>
        <div class="an-stat">
            <div class="an-stat-icone an-stat-icone--pausado">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="6" y="4" width="4" height="16" />
                    <rect x="14" y="4" width="4" height="16" />
                </svg>
            </div>
            <div class="an-stat-txt">
                <span class="an-stat-num"><?php echo $pausados ?></span>
                <span class="an-stat-label">Pausados</span>
            </div>
        </div>
    </div>

    <ul class="an-filtros">
        <li class="an-filtro js-filtro ativo" onclick="filtro(this, 'anuncio', 0)">Todos</li>
        <li class="an-filtro js-filtro" onclick="filtro(this, 'anuncio', 1)">Ativos</li>
        <li class="an-filtro js-filtro" onclick="filtro(this, 'anuncio', 2)">Pausados</li>
    </ul>

    <div class="an-grid local-filtro">
        <?php
        $sql = request("servicos?id_prestador=eq.{$id}");
        if (!empty($sql) && !isset($sql['error'])):
            foreach ($sql as $s):
                $horaI  = !empty($s['hora_inicio']) ? date("H:i", strtotime($s['hora_inicio'])) : '--:--';
                $horaF  = !empty($s['hora_fim'])    ? date("H:i", strtotime($s['hora_fim']))    : '--:--';
                $dur    = !empty($s['duracao'])      ? date("H:i", strtotime($s['duracao']))      : '--:--';
                $ativo  = $s['status'];
                $reservados = request("contratados?id_prestador=eq.{$id}&select=count");
                $numRes = $reservados[0]['count'] ?? 0;

                $precoFmt = number_format($s['preco_servico'] ?? 0, 2, ',', '.');
                $tipo_cobrado = $s['tipo_cobrado'] ?? 'Hora';
        ?>
                <div class="an-card">
                    <span class="an-badge <?php echo $ativo ? 'an-badge--ativo' : 'an-badge--pausado' ?>">
                        <?php echo $ativo ? 'Ativo' : 'Pausado' ?>
                    </span>

                    <div class="an-card-img">
                        <img src="<?php echo htmlspecialchars($s['imagem']) ?>" alt="<?php echo htmlspecialchars($s['nome']) ?>">
                    </div>

                    <div class="an-card-corpo">
                        <h3><?php echo htmlspecialchars($s['nome']) ?></h3>
                        <p><?php echo htmlspecialchars($s['descricao']) ?></p>

                        <div class="an-card-meta">
                            <div class="an-meta-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                                <span>R$ <strong><?php echo $precoFmt ?></strong> / <?php echo strtolower($tipo_cobrado) ?></span>
                            </div>

                            <div class="an-meta-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                                <span><?php echo $horaI . ' – ' . $horaF ?></span>
                            </div>
                            <div class="an-meta-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                                <span><strong><?php echo $numRes ?></strong> reservas</span>
                            </div>
                        </div>

                        <div class="an-card-acoes">
                            <button class="an-btn-acao an-btn-editar"
                                onclick="abrirModal('editar','<?php echo $s['id'] ?>')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                                Editar
                            </button>
                            <button class="an-btn-acao an-btn-pausar"
                                onclick="abrirModal('pausar','<?php echo $s['id'] ?>')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <?php if ($ativo): ?>
                                        <rect x="6" y="4" width="4" height="16" />
                                        <rect x="14" y="4" width="4" height="16" />
                                    <?php else: ?>
                                        <polygon points="5,3 19,12 5,21 5,3" />
                                    <?php endif; ?>
                                </svg>
                                <?php echo $ativo ? 'Pausar' : 'Ativar' ?>
                            </button>
                            <button class="an-btn-acao an-btn-excluir"
                                onclick="abrirModal('excluir','<?php echo $s['id'] ?>')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3,6 5,6 21,6" />
                                    <path d="M19 6l-1 14H6L5 6" />
                                    <path d="M10 11v6M14 11v6" />
                                    <path d="M9 6V4h6v2" />
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
            <div class="an-vazio">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                    <polyline points="14,2 14,8 20,8" />
                    <line x1="12" y1="18" x2="12" y2="12" />
                    <line x1="9" y1="15" x2="15" y2="15" />
                </svg>
                <h3>Nenhum serviço cadastrado</h3>
                <p>Crie seu primeiro serviço e comece a atender seu condomínio!</p>
            </div>
        <?php endif; ?>
    </div>

    <?php
    $todosPedidos = request("contratados?id_prestador=eq.{$id}&select=*&order=dia.desc,hora.desc");
    $pendentes = array_filter($todosPedidos ?? [], fn($c) => ($c['confirmado'] ?? '') === 'pendente');
    $confirmados = array_filter($todosPedidos ?? [], fn($c) => ($c['confirmado'] ?? '') === 'confirmado');
    $concluidos = array_filter($todosPedidos ?? [], fn($c) => ($c['confirmado'] ?? '') === 'concluido');
    $nPendentes = count($pendentes);
    $nConfirmados = count($confirmados);
    $nConcluidos = count($concluidos);
    ?>

    <section class="an-contratos">

        <!-- Cabeçalho da seção -->
        <div class="an-contratos-header">
            <div class="an-contratos-titulo-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 11l3 3L22 4" />
                    <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
                </svg>
                <h2>Solicitações de Serviço</h2>
            </div>

            <!-- Filtros dos contratos -->
            <ul class="an-contratos-filtros" id="contratosFiltros">
                <li class="an-cfiltro js-filtro ativo" onclick="filtro(this, 'contratos', 'todos')">
                    Todos
                    <?php if (($nPendentes + $nConfirmados + $nConcluidos) > 0): ?>
                        <span class="an-cfiltro-count"><?php echo $nPendentes + $nConfirmados + $nConcluidos ?></span>
                    <?php endif; ?>
                </li>
                <li class="an-cfiltro js-filtro" onclick="filtro(this, 'contratos', 'pendente')">
                    <span class="an-cfiltro-dot an-cfiltro-dot--pendente"></span>
                    Pendentes
                    <?php if ($nPendentes > 0): ?>
                        <span class="an-cfiltro-count an-cfiltro-count--pendente"><?php echo $nPendentes ?></span>
                    <?php endif; ?>
                </li>
                <li class="an-cfiltro js-filtro" onclick="filtro(this, 'contratos', 'confirmado')">
                    <span class="an-cfiltro-dot an-cfiltro-dot--confirmado"></span>
                    Confirmados
                    <?php if ($nConfirmados > 0): ?>
                        <span class="an-cfiltro-count an-cfiltro-count--confirmado"><?php echo $nConfirmados ?></span>
                    <?php endif; ?>
                </li>
                <li class="an-cfiltro js-filtro" onclick="filtro(this, 'contratos', 'concluido')">
                    <span class="an-cfiltro-dot an-cfiltro-dot--concluido"></span>
                    Concluídos
                    <?php if ($nConcluidos > 0): ?>
                        <span class="an-cfiltro-count an-cfiltro-count--concluido"><?php echo $nConcluidos ?></span>
                    <?php endif; ?>
                </li>
            </ul>
        </div>

        <!-- Lista de contratos -->
        <div class="an-contratos-lista local-filtro-contrato" id="contratosList">
            <?php if (empty($todosPedidos) || isset($todosPedidos['error'])): ?>
                <div class="an-contratos-vazio">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 8v4m0 4h.01" />
                    </svg>
                    <p>Nenhuma solicitação recebida ainda.</p>
                </div>
            <?php else: ?>
                <?php foreach ($todosPedidos as $p):
                    $diaFmt    = date('d/m/Y', strtotime($p['dia']));
                    $horaFmt   = substr($p['hora'], 0, 5);
                    $status    = $p['confirmado'] ?? 'pendente';
                    $statusMap = [
                        'pendente'   => ['label' => 'Pendente',   'cls' => 'pendente'],
                        'confirmado' => ['label' => 'Confirmado', 'cls' => 'confirmado'],
                        'concluido'  => ['label' => 'Concluído',  'cls' => 'concluido'],
                        'cancelado'  => ['label' => 'Cancelado',  'cls' => 'cancelado'],
                    ];
                    $st = $statusMap[$status] ?? ['label' => ucfirst($status), 'cls' => 'pendente'];
                ?>
                    <div class="an-contrato-card" data-status="<?php echo $status ?>">
                        <div class="an-contrato-status-bar an-contrato-status-bar--<?php echo $st['cls'] ?>"></div>

                        <div class="an-contrato-body">
                            <div class="an-contrato-info">
                                <span class="an-contrato-nome"><?php echo htmlspecialchars($p['nome_servico']) ?></span>
                                <span class="an-contrato-cliente">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                        <circle cx="12" cy="7" r="4" />
                                    </svg>
                                    <?php echo htmlspecialchars($p['nome_cliente']) ?>
                                </span>
                                <span class="an-contrato-data">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" />
                                    </svg>
                                    <?php echo $diaFmt ?> as <?php echo $horaFmt ?>
                                </span>
                                <?php if (!empty($p['observacao'])): ?>
                                    <span class="an-contrato-obs">"<?php echo htmlspecialchars($p['observacao']) ?>"</span>
                                <?php endif; ?>
                            </div>

                            <div class="an-contrato-direita">
                                <span class="an-contrato-badge an-contrato-badge--<?php echo $st['cls'] ?>">
                                    <?php echo $st['label'] ?>
                                </span>

                                <?php if ($status === 'pendente'): ?>
                                    <div class="an-contrato-acoes">
                                        <form method="POST" action="./controls/servico.act.php" style="display:inline;">
                                            <input type="hidden" name="id_contrato" value="<?php echo $p['id'] ?>">
                                            <input type="hidden" name="acao" value="aceitar">
                                            <button type="submit" class="an-btn-aceitar">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20,6 9,17 4,12" />
                                                </svg>
                                                Aceitar
                                            </button>
                                        </form>
                                        <form method="POST" action="./controls/servico.act.php" style="display:inline;">
                                            <input type="hidden" name="id_contrato" value="<?php echo $p['id'] ?>">
                                            <input type="hidden" name="acao" value="recusar">
                                            <button type="submit" class="an-btn-recusar">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="18" y1="6" x2="6" y2="18" />
                                                    <line x1="6" y1="6" x2="18" y2="18" />
                                                </svg>
                                                Recusar
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                                <?php if ($status === 'confirmado'): ?>
                                    <div class="an-contrato-acoes">
                                        <button type="button" class="an-btn-recusar"
                                            onclick="abrirModal('confirmar_cancelamento', '<?php echo $p['id'] ?>|prestador')">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="18" y1="6" x2="6" y2="18" />
                                                <line x1="6" y1="6" x2="18" y2="18" />
                                            </svg>
                                            Cancelar
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include('./includes/rodape.php'); ?>