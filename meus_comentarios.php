<?php
require_once './includes/funcoes.php';
exigirLogin();

include('./includes/head.php');
include('./includes/topo.php');
?>

<link rel="stylesheet" href="./css/meus_comentarios.css">

<main class="pag-meus-comentarios">

    <!-- ===== HERO ===== -->
    <div class="mc-hero">
        <div class="mc-hero-inner">
            <div>
                <span class="mc-subtag">Gerenciamento</span>
                <h1>Avaliações dos Meus Serviços</h1>
                <p>Visualize e modere os comentários recebidos nos seus serviços</p>
            </div>
            <a href="./historico.php" class="mc-btn-voltar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5m7-7-7 7 7 7" />
                </svg>
                Voltar
            </a>
        </div>
    </div>

    <div class="mc-container">
        <div class="mc-filtros">
            <div class="mc-filtro-group">
                <label>Filtrar por Serviço</label>
                <select id="filtro-servico" onchange="filtrarComentarios()">
                    <option value="">Todos os serviços</option>
                    <?php
                    $servicos = request("servicos?id_prestador=eq.$id&select=id,nome&order=nome.asc", "GET");
                    if (!empty($servicos) && !isset($servicos['error'])):
                        foreach ($servicos as $s):
                    ?>
                            <option value="<?= $s['id'] ?>"><?= $s['nome'] ?></option>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>

            <div class="mc-filtro-group">
                <label>Filtrar por Status</label>
                <select id="filtro-status" onchange="filtrarComentarios()">
                    <option value="">Todos</option>
                    <option value="valido">Válidos</option>
                    <option value="invalido">Inválidos</option>
                </select>
            </div>
        </div>

        <div class="mc-lista">
            <?php
            $avaliacoes = request(
                "avaliacoes"
                    . "?select=id,nota,comentario,nome_servico,nome_cliente,data,horario,editado_em"
                    . ",servicos(id,id_prestador)"
                    . "&servicos.id_prestador=eq.$id"
                    . "&order=data.desc",
                "GET"
            );

            if (!empty($avaliacoes) && !isset($avaliacoes['error'])):
                foreach ($avaliacoes as $av):
                    $eh_invalido = strpos($av['comentario'], '[Comentário marcado como inválido') === 0;
                    $status_classe = $eh_invalido ? 'invalido' : 'valido';
                    $data_fmt = !empty($av['data']) ? date('d/m/Y', strtotime($av['data'])) : '—';
                    $hora_fmt = !empty($av['horario']) ? date('H:i', strtotime($av['horario'])) : '—';
            ?>
                    <div class="mc-card mc-card--<?= $status_classe ?>" data-servico="<?= $av['servicos']['id'] ?>" data-status="<?= $status_classe ?>">
                        <div class="mc-card-header">
                            <div class="mc-card-title">
                                <h3><?= htmlspecialchars($av['nome_servico']) ?></h3>
                                <p class="mc-cliente">Cliente: <?= $av['nome_cliente']  ?? 'Não informado' ?></p>
                            </div>
                            <div class="mc-card-nota">
                                <?php if (!$eh_invalido): ?>
                                    <div class="mc-stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="mc-star <?= $i <= $av['nota'] ? 'active' : '' ?>">★</span>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="mc-nota-valor"><?= $av['nota'] ?>/5</span>
                                <?php else: ?>
                                    <span class="mc-status-invalido">INVÁLIDO</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mc-card-body">
                            <div class="mc-comentario">
                                <?= nl2br(htmlspecialchars($av['comentario'])) ?>
                            </div>
                            <div class="mc-card-meta">
                                <span>Data: <?= $data_fmt ?> às <?= $hora_fmt ?></span>
                                <?php if (!empty($av['editado_em'])): ?>
                                    <span>Editado: <?= date('d/m/Y H:i', strtotime($av['editado_em'])) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mc-card-acoes">
                            <?php if (!$eh_invalido): ?>
                                <button class="mc-btn mc-btn--moderar" onclick="abrirModal('moderar_comentario','<?= $av['id'] ?>')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                        <line x1="12" y1="8" x2="12" y2="12" />
                                        <line x1="12" y1="16" x2="12.01" y2="16" />
                                    </svg>
                                    Marcar como Inválido
                                </button>
                            <?php else: ?>
                                <button class="mc-btn mc-btn--desfazer" onclick="abrirModal('desfazer_moderacao','<?= $av['id'] ?>')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="1 4 1 10 7 10" />
                                        <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10" />
                                    </svg>
                                    Desfazer
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php
                endforeach;
            else:
                ?>
                <div class="mc-vazio">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26 12,2" />
                    </svg>
                    <p>Nenhuma avaliação recebida ainda</p>
                    <a href="./servicos.php">Ver meus serviços</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include("./includes/rodape.php"); ?>

<script>
    function filtrarComentarios() {
        const filtroServico = document.getElementById('filtro-servico').value;
        const filtroStatus = document.getElementById('filtro-status').value;
        const cards = document.querySelectorAll('.mc-card');

        cards.forEach(card => {
            const servico = card.dataset.servico;
            const status = card.dataset.status;

            let mostrar = true;

            if (filtroServico && servico !== filtroServico) {
                mostrar = false;
            }

            if (filtroStatus && status !== filtroStatus) {
                mostrar = false;
            }

            card.style.display = mostrar ? 'block' : 'none';
        });
    }
</script>