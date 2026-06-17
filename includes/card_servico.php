<?php
function renderCardServico($servico, $tipo = 'publico', $id_usuario_logado = null) {
    if (empty($servico)) {
        return;
    }

    $horaInicio = !empty($servico['hora_inicio']) ? date('H:i', strtotime($servico['hora_inicio'])) : '--:--';
    $horaFim = !empty($servico['hora_fim']) ? date('H:i', strtotime($servico['hora_fim'])) : '--:--';
    $duracao = !empty($servico['duracao']) ? date('H:i', strtotime($servico['duracao'])) : '--:--';
    
    $imagem = $servico['imagem'] ?? '';
    $nomeServico = htmlspecialchars($servico['nome'] ?? '');
    $descricao = htmlspecialchars($servico['descricao'] ?? '');
    $precoRaw = isset($servico['preco_servico']) ? (float)$servico['preco_servico'] : null;
    $preco = $precoRaw;
    $tipoCobrado = $servico['tipo_cobrado'] ?? 'Hora';
    $categoria = $servico['categorias']['nome'] ?? 'Sem categoria';
    $avaliacao = $servico['nota_geral'] ?? '0';
    $statusServico = $servico['status'] ?? true;

    // Formata o preço com tratamento de nulo/zero
    if ($precoRaw === null || $precoRaw <= 0) {
        $precoDisplay = '<span class="card-preco-indefinido">A combinar</span>';
    } else {
        $precoDisplay = 'R$ ' . number_format($precoRaw, 2, ',', '.') . '<span> / ' . htmlspecialchars($tipoCobrado) . '</span>';
    }
    
    $nomePrestador = htmlspecialchars($servico['usuarios']['nome'] ?? '');
    $avatarInicial = substr($nomePrestador, 0, 1);
    
    if ($tipo === 'publico') {
        ?>
        <div class="card-servico">
            <div class="card-img-wrap">
                <img src="<?php echo htmlspecialchars($imagem) ?>" alt="<?php echo $nomeServico ?>">
                <span class="card-categoria"><?php echo htmlspecialchars($categoria) ?></span>
                <span class="card-avaliacao"><?php echo htmlspecialchars($avaliacao) ?></span>
            </div>
            <div class="card-corpo">
                <h3 class="card-titulo"><?php echo $nomeServico ?></h3>
                <p class="card-desc"><?php echo $descricao ?></p>
                <div class="card-info">
                    <div class="card-horario">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12,6 12,12 16,14" />
                        </svg>
                        <?php echo $horaInicio ?> – <?php echo $horaFim ?>
                    </div>
                    <div class="card-preco"><?php echo $precoDisplay ?></div>
                </div>
            </div>
            <div class="card-rodape">
                <div class="prestador">
                    <div class="prestador-avatar"><?php echo htmlspecialchars($avatarInicial) ?></div>
                    <span class="prestador-nome"><?php echo $nomePrestador ?></span>
                </div>
                <?php
                if (!empty($id_usuario_logado) && $id_usuario_logado != $servico['id_prestador']):
                ?>
                    <div class="card-rodape-acoes">
                        <form action="./controls/mensagens.act.php" method="POST" style="display:inline;">
                            <input type="hidden" name="acao" value="iniciar">
                            <input type="hidden" name="id_destinatario" value="<?= $servico['id_prestador'] ?>">
                            <button type="submit" class="btn-conversar" title="Conversar com Prestador">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            </button>
                        </form>
                        <button class="btn-agendar"
                            onclick="abrirModal('agendar','<?php echo htmlspecialchars($servico['id']) ?>')">Agendar</button>
                    </div>
                <?php
                endif;
                ?>
            </div>
        </div>
        <?php
    } elseif ($tipo === 'anunciar') {
        $numReservas = $servico['num_reservas'] ?? 0;
        $statusBadge = $statusServico ? 'an-badge--ativo' : 'an-badge--pausado';
        $statusTexto = $statusServico ? '● Ativo' : '⏸ Pausado';
        ?>
        <div class="an-card">
            <span class="an-badge <?php echo $statusBadge ?>">
                <?php echo $statusTexto ?>
            </span>

            <div class="an-card-img">
                <img src="<?php echo htmlspecialchars($imagem) ?>" alt="<?php echo $nomeServico ?>">
            </div>

            <div class="an-card-corpo">
                <h3><?php echo $nomeServico ?></h3>
                <p><?php echo $descricao ?></p>

                <div class="an-card-meta">
                    <div class="an-meta-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 6v6l4 2" />
                        </svg>
                        <span><?php echo $horaInicio . ' – ' . $horaFim ?></span>
                    </div>
                    <div class="an-meta-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                        <span><strong><?php echo htmlspecialchars($numReservas) ?></strong> reservas</span>
                    </div>
                </div>
                
                <div class="an-card-preco">
                    <?php if ($precoRaw !== null && $precoRaw > 0): ?>
                        <span>R$ <strong><?php echo number_format($precoRaw, 2, ',', '.') ?></strong> / <?php echo htmlspecialchars($tipoCobrado) ?></span>
                    <?php else: ?>
                        <span class="card-preco-indefinido">A combinar</span>
                    <?php endif; ?>
                </div>

                <div class="an-card-acoes">
                    <button class="an-btn-acao an-btn-editar"
                        onclick="abrirModal('editar','<?php echo htmlspecialchars($servico['id']) ?>')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                        Editar
                    </button>
                    <button class="an-btn-acao an-btn-pausar"
                        onclick="abrirModal('pausar','<?php echo htmlspecialchars($servico['id']) ?>')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <?php if ($statusServico): ?>
                                <rect x="6" y="4" width="4" height="16" />
                                <rect x="14" y="4" width="4" height="16" />
                            <?php else: ?>
                                <polygon points="5,3 19,12 5,21 5,3" />
                            <?php endif; ?>
                        </svg>
                        <?php echo $statusServico ? 'Pausar' : 'Ativar' ?>
                    </button>
                    <button class="an-btn-acao an-btn-excluir"
                        onclick="abrirModal('excluir','<?php echo htmlspecialchars($servico['id']) ?>')">
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
    }
}
?>