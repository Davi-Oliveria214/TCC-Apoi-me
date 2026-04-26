<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

require(__DIR__ . '/../conexao.php');
$resp = $_POST['item'];
$tipo = $_POST['type'] ?? 'servicos';

if ($tipo === "servicos") :
    if ($resp == 0) {
        $sql = request("servicos?status=eq.true&select=*", "GET");
    } else {
        $sql = request("servicos?categoria=eq.$resp&status=eq.true&select=*", "GET");
    }

    if (!empty($sql) && !isset($sql['error'])) :
        foreach ($sql as $servico) :
            $horaInicio = date('H:i', strtotime($servico['hora_inicio']));
            $horaFim = date('H:i', strtotime($servico['hora_fim']));
?>
            <div class='card card-servico' data-id="<?php echo $servico['id'] ?>">
                <img src="<?php echo $servico['imagem'] ?>" alt=''>
                <div>
                    <div class='info-card'>
                        <h2 class='titulo-card'><?php echo $servico['nome'] ?></h2>
                        <p><?php echo $servico['descricao'] ?></p>
                    </div>
                    <div class='cronograma'>
                        <p>Das <time datetime='$horaInicio'><?php echo $horaInicio ?></time>
                            Até <time datetime='$horaFim'><?php echo $horaFim ?></time></p>
                    </div>
                    <div class='box-btn'>
                        <a href='' class='btn'>Agendar serviço</a>
                    </div>
                </div>
            </div>
        <?php
        endforeach;
    else :
        echo "<h2 id=aviso>Nenhum serviço encontrado</h2>";
    endif;

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
            $hora_inicio = !empty($s['hora_inicio']) ? date("H:i", strtotime($s['hora_inicio'])) : '--:--';
            $hora_fim    = !empty($s['hora_fim']) ? date("H:i", strtotime($s['hora_fim'])) : '--:--';
            $duracao    = !empty($s['duracao']) ? date("H:i", strtotime($s['duracao'])) : '--:--';

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
                        <?php
                        $reservados = request("contratados?id_prestador=eq.{$id}&select=count");
                        ?>
                        <span class="num"><?php echo $reservados[0]['conut'] ?? 0; ?></span> reservas este mês
                    </div>

                    <div class="card-acoes">
                        <button class="btn-acao btn-link" onclick="abrirEdicao('<?php echo $s['id'] ?>', '<?php echo $s['nome'] ?>', 
                                                '<?php echo $s['imagem'] ?>',
                                                '<?php echo $hora_inicio ?>', 
                                                '<?php echo $hora_fim ?>',
                                                '<?php echo $duracao ?>',
                                                '<?php echo $estaAtivo ?>',
                                                '<?php echo $s['descricao'] ?>')">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                            </svg>
                            Editar
                        </button>

                        <button class="btn-acao btn-link" onclick="pausarServico(<?php echo $s['id'] ?>, <?php echo $estaAtivo ?>)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="6" y="4" width="4" height="16" />
                                <rect x="14" y="4" width="4" height="16" />
                            </svg>
                            <?php echo $estaAtivo ? 'Pausar' : 'Ativar' ?>
                        </button>

                        <button class="btn-acao btn-link" onclick="excluirOferecidos(<?php echo $s['id'] ?>)">
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
        echo "<div class='aviso-vazio'>Nenhum serviço encontrado</div>";
    endif;

endif;