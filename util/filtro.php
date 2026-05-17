<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

require(__DIR__ . '/../conexao.php');
$resp = $_POST['item'] ?? 0;
$tipo = $_POST['type'] ?? 'servicos';

if ($tipo === "servicos") :
    $id = $_SESSION['id'] ?? null;
    if ($resp == 0) {
        $sql = request("servicos?status=eq.true&select=*,categorias(nome),usuarios(nome)", "GET");
    } else {
        $sql = request("servicos?categoria=eq.$resp&status=eq.true&select=*,categorias(nome),usuarios(nome)", "GET");
    }

    if (!empty($sql) && !isset($sql['error'])) :
        foreach ($sql as $servico) :
            $horaInicio = date('H:i', strtotime($servico['hora_inicio']));
            $horaFim = date('H:i', strtotime($servico['hora_fim']));
            $imagem = $servico['imagem']
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
    else:
        ?>
        <div class="sv-vazio-grid">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.35-4.35" />
            </svg>
            <h3>Nenhum serviço encontradol</h3>
            <a href="./anunciar.php" class="sv-btn-agendar">Anunciar serviço</a>
        </div>
        <?php endif;

elseif ($tipo === 'anuncio') :
    $id = $_SESSION['id'];

    $sql = "";
    switch ($resp) {
        case (0):
            $sql = request("servicos?id_prestador=eq.{$id}");
            break;
        case (1):
            $sql = request("servicos?id_prestador=eq.{$id}&status=eq.true");
            break;
        case (2):
            $sql = request("servicos?id_prestador=eq.{$id}&status=eq.false");
            break;
        default:
            $sql = request("servicos?id_prestador=eq.{$id}");
            break;
    }

    if (!empty($sql) && !isset($sql['error'])):
        foreach ($sql as $s):
            $horaI  = !empty($s['hora_inicio']) ? date("H:i", strtotime($s['hora_inicio'])) : '--:--';
            $horaF  = !empty($s['hora_fim'])    ? date("H:i", strtotime($s['hora_fim']))    : '--:--';
            $dur    = !empty($s['duracao'])      ? date("H:i", strtotime($s['duracao']))      : '--:--';
            $ativo  = $s['status'];
            $reservados = request("contratados?id_prestador=eq.{$id}&select=count");
            $numRes = $reservados[0]['count'] ?? 0;
        ?>
            <div class="an-card">
                <span class="an-badge <?php echo $ativo ? 'an-badge--ativo' : 'an-badge--pausado' ?>">
                    <?php echo $ativo ? '● Ativo' : '⏸ Pausado' ?>
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
    <?php endif;
elseif ($tipo === 'contratos'):

    $id = $_SESSION['id'];

    $sql = "";
    if ($resp === 'todos') {
        $sql = request("contratados?id_prestador=eq.{$id}&select=*&order=dia.desc,hora.desc");
    } else {
        $sql = request("contratados?id_prestador=eq.{$id}&confirmado=eq.{$resp}&select=*&order=dia.desc,hora.desc");
    }

    if (empty($sql) || isset($sql['error'])): ?>
        <div class="an-contratos-vazio">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <path d="M12 8v4m0 4h.01" />
            </svg>
            <p>Nenhuma solicitação recebida ainda.</p>
        </div>
    <?php else: ?>
        <?php foreach ($sql as $p):
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
                            <?php echo $diaFmt ?> às <?php echo $horaFmt ?>
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
                                <form method="POST" action="controls/servico.act.php" style="display:inline;">
                                    <input type="hidden" name="id_contrato" value="<?php echo $p['id'] ?>">
                                    <input type="hidden" name="acao" value="aceitar">
                                    <button type="submit" class="an-btn-aceitar">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20,6 9,17 4,12" />
                                        </svg>
                                        Aceitar
                                    </button>
                                </form>
                                <form method="POST" action="controls/servico.act.php" style="display:inline;">
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
                                <form method="POST" action="controls/servico.act.php" style="display:inline;"
                                    onsubmit="return confirm('Tem certeza que deseja cancelar este serviço confirmado? O cliente será notificado por e-mail.')">
                                    <input type="hidden" name="resp" value="<?php echo $p['id'] ?>">
                                    <input type="hidden" name="acao" value="cancelar">
                                    <input type="hidden" name="origem" value="prestador">
                                    <button type="submit" class="an-btn-recusar">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18" />
                                            <line x1="6" y1="6" x2="18" y2="18" />
                                        </svg>
                                        Cancelar
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
<?php endif;
endif;