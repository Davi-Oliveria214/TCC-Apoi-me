<?php
require_once(__DIR__ . '/../conexao.php');
session_start();

$valor = $_GET['pesquisa'] ?? '';
$pagina = $_GET['pagina'] ?? 'publico';

if ($pagina === 'anunciar') {
    $id = $_SESSION['id'];

    $endpoint = "servicos?id_prestador=eq.$id";

    if (!empty($valor)) {
        $endpoint .= "&nome=ilike.*$valor*";
    }
} else {
    $endpoint = "servicos?status=eq.true&select=*,categorias(nome),usuarios(nome)";

    if (!empty($valor)) {
        $endpoint .= "&nome=ilike.*$valor*";
    }

    $endpoint .= "&order=criado.desc&limit=10";
}

$servicos = request($endpoint, "GET");

if ($pagina !== 'anunciar') :
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
                    if (!empty($id) || $_SESSION['id'] != $servico['id_prestador']):
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
            <h3>Nenhum serviço disponível</h3>
            <p>Seja o primeiro a anunciar um serviço no seu condomínio!</p>
            <a href="./anunciar.php" class="sv-btn-agendar">Anunciar serviço</a>
        </div>
        <?php endif;
else :
    if (!empty($servicos) && !isset($servicos['error'])):
        foreach ($servicos as $s):
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
endif;
?>