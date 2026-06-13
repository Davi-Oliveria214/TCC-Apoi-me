<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

require(__DIR__ . '/../conexao.php');
$resp = $_POST['item'] ?? 0;
$tipo = $_POST['type'] ?? 'servicos';

if ($tipo === "servicos") :
    require_once(__DIR__ . '/../includes/card_servico.php');
    $id = $_SESSION['id'] ?? null;
    if ($resp == 0) {
        $sql = request("servicos?status=eq.true&select=*,categorias(nome),usuarios(nome)", "GET");
    } else {
        $sql = request("servicos?categoria=eq.$resp&status=eq.true&select=*,categorias(nome),usuarios(nome)", "GET");
    }

    if (!empty($sql) && !isset($sql['error'])) :
        foreach ($sql as $servico) :
            renderCardServico($servico, 'publico', $_SESSION['id'] ?? null);
        endforeach;
    else:
        ?>
        <div class="sv-vazio-grid">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.35-4.35" />
            </svg>
            <h3>Nenhum serviço encontrado</h3>
            <a href="./anunciar.php" class="sv-btn-agendar">Anunciar serviço</a>
        </div>
        <?php endif;

elseif ($tipo === 'anuncio') :
    require_once(__DIR__ . '/../includes/card_servico.php');
    $id = $_SESSION['id'];

    $sql = "";
    switch ($resp) {
        case (0):
            $sql = request("servicos?id_prestador=eq.{$id}&select=*,categorias(nome),usuarios(nome)");
            break;
        case (1):
            $sql = request("servicos?id_prestador=eq.{$id}&status=eq.true&select=*,categorias(nome),usuarios(nome)");
            break;
        case (2):
            $sql = request("servicos?id_prestador=eq.{$id}&status=eq.false&select=*,categorias(nome),usuarios(nome)");
            break;
        default:
            $sql = request("servicos?id_prestador=eq.{$id}&select=*,categorias(nome),usuarios(nome)");
            break;
    }

    if (!empty($sql) && !isset($sql['error'])):
        foreach ($sql as $s):
            // Busca reservas específicas deste serviço
            $reservados = request("contratados?id_servico=eq.{$s['id']}&select=count");
            $s['num_reservas'] = $reservados[0]['count'] ?? 0;
            
            renderCardServico($s, 'anunciar', $_SESSION['id']);
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