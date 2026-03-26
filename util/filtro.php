<?php
require(__DIR__ . '/../conexao.php');
$resp = $_POST['resp'];

if ($resp == 0) {
    $sql = request("servicos?select=id,nome,imagem,descricao,hora_inicio,hora_fim,dia", "GET");
} else {
    $sql = request("servicos?categoria=eq.$resp&select=id,nome,imagem,descricao,hora_inicio,hora_fim,dia", "GET");
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