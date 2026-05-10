<?php
require_once(__DIR__ . '/../includes/funcoes.php');
exigirMetodo();

require(__DIR__ . '/../conexao.php');
$resp = $_POST['item'] ?? 0;
$tipo = $_POST['type'] ?? 'servicos';

if ($tipo === "servicos") :
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
                    <button class="btn-agendar">Agendar</button>
                </div>
            </div>
        <?php
        endforeach;
    else :
        ?>
        <div class='aviso-vazio'>Nenhum serviço encontrado</div>
        <?php
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
        foreach ($sql as $servico):
            $hora_inicio = date("H:i", strtotime($servico['hora_inicio']));
            $hora_fim    =  date("H:i", strtotime($servico['hora_fim']));
            $duracao    = date("H:i", strtotime($servico['duracao']));
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
                            <?php echo $hora_inicio ?> – <?php echo $hora_fim ?>
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
        <div class='aviso-vazio'>Você ainda não está oferecendo nenhum serviço</div>
<?php
    endif;

endif;
