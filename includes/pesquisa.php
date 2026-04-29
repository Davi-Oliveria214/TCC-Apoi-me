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
    $endpoint = "servicos?status=eq.true";

    if (!empty($valor)) {
        $endpoint .= "&nome=ilike.*$valor*";
    }

    $endpoint .= "&order=criado.desc&limit=10";
}

$servicos = request($endpoint, "GET");

if ($pagina === 'publico') :
    if (!empty($servicos) && !isset($servicos['error'])):
        shuffle($servicos);
        foreach ($servicos as $servico) :
            $horaInicio = $servico['hora_inicio'] != null ? date('H:i', strtotime($servico['hora_inicio'])) : "Não informado";
            $horaFim = date('H:i', strtotime($servico['hora_fim']));
            $imagem = $servico['imagem'];
            $duracao = date('H:i', strtotime($servico['duracao']));
?>
            <div class='card card-servico' data-id='<?php echo $servico["id"] ?>'>
                <img src='<?php echo $imagem ?>' alt=''>
                <div>
                    <div class='info-card'>
                        <h2 class='titulo-card'><?php echo $servico['nome'] ?></h2>
                        <p><?php echo $servico['descricao'] ?></p>
                        <span><?php echo $horaInicio ?></span>
                    </div>
                    <div class='box-btn'>
                        <button onclick="abrirModalAgendar('<?php echo $servico['id'] ?>', '<?php echo $servico['nome'] ?>', '<?php echo $imagem ?>', '<?php echo $horaInicio ?>', '<?php echo $horaFim ?>', '<?php echo $duracao  ?>')" class="btn">
                            Agendar serviço
                        </button>
                    </div>
                </div>
            </div>
        <?php
        endforeach;
    else :
        echo "<h2 id='aviso' style='text-align: center;'>Nenhum serviço disponível no momento.</h2>";
    endif;
else :
    if (!empty($servicos) && !isset($servicos['error'])):
        foreach ($servicos as $s):
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

                        <button class="btn-acao btn-link" onclick="pausarServico('<?php echo $s['id'] ?>', '<?php echo $estaAtivo ?>')">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="6" y="4" width="4" height="16" />
                                <rect x="14" y="4" width="4" height="16" />
                            </svg>
                            <?php echo $estaAtivo ? 'Pausar' : 'Ativar' ?>
                        </button>

                        <button class="btn-acao btn-link" onclick="excluirOferecidos('<?php echo $s['id'] ?>')">
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
        ?>
        <div class='aviso-vazio'>Você ainda não está oferecendo nenhum serviço.</div>
<?php
    endif;
endif;
?>