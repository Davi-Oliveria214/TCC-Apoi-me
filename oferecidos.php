<?php
include('./includes/head.php');
include('./includes/topo.php');
?>

<main class="principal">

    <!-- CABEÇALHO -->
    <div class="secao-header">
        <div class="secao-header-info">
            <h2>Meus Serviços</h2>
            <p>GERENCIE OS SERVIÇOS QUE VOCÊ OFERECE AO CONDOMÍNIO</p>
        </div>
        <a href="#" class="btn-novo-servico">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Novo Serviço
        </a>
    </div>

    <!-- STATS -->
    <div class="stats-bar">
        <?php
        $res = request("servicos?id_prestador=eq.{$id}&select=count");
        $total = $res[0]['count'];

        $resAtivos = request("servicos?id_prestador=eq.{$id}&status=eq.true&select=count");
        $ativos = $resAtivos[0]['count'] ?? 0;
        ?>

        <div class="stat-item">
            <div class="stat-icone">📋</div>
            <div class="stat-texto">
                <p>Total</p>
                <strong><?php echo !empty($total) ? $total : 0 ?></strong>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icone">✅</div>
            <div class="stat-texto">
                <p>Ativos</p>
                <strong><?php echo !empty($ativos) ? $ativos : 0 ?></strong>
            </div>
        </div>
        <!-- <div class="stat-item">
            <div class="stat-icone">📅</div>
            <div class="stat-texto">
                <p>Reservas este mês</p>
                <strong>12</strong>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icone">⭐</div>
            <div class="stat-texto">
                <p>Avaliação</p>
                <strong>4.8</strong>
            </div>
        </div> -->
    </div>

    <!-- FILTROS -->
    <div class="filtros">
        <button class="filtro-btn ativo">Todos</button>
        <button class="filtro-btn">Ativos</button>
        <button class="filtro-btn">Pausados</button>
        <button class="filtro-btn">Inativos</button>
    </div>

    <!-- GRID DE SERVIÇOS -->
    <div class="grid-servicos">
        <?php
        $sql = request("servicos?id_prestador=eq.{$id}");

        if (!empty($sql) && !isset($sql['error'])):
            foreach ($sql as $s):
                $hora_inicio = !empty($s['hora_inicio']) ? date("H:i", strtotime($s['hora_inicio'])) : '--:--';
                $hora_fim    = !empty($s['hora_fim']) ? date("H:i", strtotime($s['hora_fim'])) : '--:--';

                $estaAtivo = $s['status'];
        ?>
                <div class="card-servico-oferecidos">
                    <span class="badge-status <?php echo $estaAtivo ?  'badge-ativo' : 'badge-pausado' ?>">
                        <?php echo $estaAtivo ?  'Ativo' : 'Pausado' ?>
                    </span>

                    <div class="card-img-wrap">
                        <img src="<?php echo $s['imagem'] ?>" alt="<?php echo $s['nome'] ?>">
                    </div>

                    <div class="card-corpo">
                        <h3 class="card-nome"><?php echo $s['nome'] ?></h3>
                        <p class="card-descricao"><?php echo $s['descricao'] ?></p>

                        <div class="card-meta">
                            <div class="meta-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                                <span><?php echo $hora_inicio . " - " . $hora_fim ?></span>
                            </div>
                        </div>

                        <div class="card-reservas">
                            <span class="num">0</span> reservas este mês
                        </div>

                        <div class="card-acoes">
                            <button class="btn-acao btn-editar" onclick="abrirEdicao('<?php echo $s['nome'] ?>', 
                                                '<?php echo addslashes($s['descricao']) ?>', 
                                                '<?php echo $s['imagem'] ?>', 
                                                '<?php echo $hora_inicio ?>', 
                                                '<?php echo $hora_fim ?>', 
                                                '<?php echo $estaAtivo ?>')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                                Editar
                            </button>

                            <button class="btn-acao btn-pausar" onclick="pausarServico(<?php echo $s['id'] ?>, <?php echo $estaAtivo ?>)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="6" y="4" width="4" height="16" />
                                    <rect x="14" y="4" width="4" height="16" />
                                </svg>
                                <?php echo $estaAtivo ? 'Pausar' : 'Ativar' ?>
                            </button>

                            <button class="btn-acao btn-excluir" onclick="excluirOferecidos(<?php echo $s['id'] ?>)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6" />
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
            echo "<div class='aviso-vazio'>Você ainda não está oferecendo nenhum serviço.</div>";
        endif;
        ?>
    </div>
</main>

<?php include('./includes/rodape.php'); ?>