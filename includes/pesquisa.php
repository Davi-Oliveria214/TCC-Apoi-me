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

if ($pagina === 'publico') :
    if (!empty($servicos) && !isset($servicos['error'])):
        foreach ($servicos as $servico) :
            $horaInicio = date('H:i', strtotime($servico['hora_inicio']));
            $horaFim = date('H:i', strtotime($servico['hora_fim']));
            $imagem = $servico['imagem'];
            $duracao = date('H:i', strtotime($servico['duracao']));
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
                    <button class="btn-agendar">Agendar</button>
                </div>
            </div>
        <?php
        endforeach;
    else :
        ?>
        <div class='aviso-vazio'>Você ainda não está oferecendo nenhum serviço.</div>
        <?php
    endif;
else :
    if (!empty($servicos) && !isset($servicos['error'])):
        foreach ($servicos as $servico):
            $horaInicio = !empty($servico['hora_inicio']) ? date("H:i", strtotime($servico['hora_inicio'])) : '--:--';
            $horaFim    = !empty($servico['hora_fim']) ? date("H:i", strtotime($servico['hora_fim'])) : '--:--';
            $duracao    = !empty($servico['duracao']) ? date("H:i", strtotime($servico['duracao'])) : '--:--';
            $imagem = $servico['imagem'];
            $estaAtivo = $servico['status'];
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
                    <button class="btn-agendar">Agendar</button>
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