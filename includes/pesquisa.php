<?php
require_once(__DIR__ . '/../conexao.php');
session_start();

$valor = $_GET['pesquisa'];

if (empty($valor)) {
    $servicos = request("servicos?select=*&order=criado.desc&limit=10", "GET");
} else {
    $servicos = request("servicos?nome=ilike.*$valor*");
}

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
?>