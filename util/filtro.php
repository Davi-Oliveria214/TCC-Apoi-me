<?php
require(__DIR__ . '/../conexao.php');
$resp = $_POST['resp'];

if ($resp == 0) {
    $sql = request("servicos?select=id,nome,imagem,descricao,horario_inicio,horario_fim,data_limite", "GET");
} else {
    $sql = request("servicos?id_categoria=eq.$resp&select=id,nome,imagem,descricao,horario_inicio,horario_fim,data_limite", "GET");
}

if (!empty($sql) && !isset($sql['error'])) {
    foreach ($sql as $servico) {
        $horaInicio = date('H:i', strtotime($servico['horario_inicio']));
        $horaFim = date('H:i', strtotime($servico['horario_fim']));

        echo "<div class='card card-servico' data-id='{$servico['id']}'>";
        echo "<img src='$servico[imagem]' alt=''>";
        echo "<div>";
        echo "<div class='info-card'>";
        echo "<h2 class='titulo-card'>$servico[nome]</h2>";
        echo "<p>$servico[descricao]</p>";
        echo "</div>";
        echo "<div class='cronograma'>";
        echo "<p>Das <time datetime='$horaInicio'>$horaInicio</time>
                    Até <time datetime='$horaFim'>$horaFim</time></p>";
        echo "<p>Data limite: <time datetime='$servico[data_limite]'>$servico[data_limite]</time></p>";
        echo "</div>";
        echo "<div class='box-btn'>";
        echo "<a href='' class='btn' >Agendar serviço</a>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<h2 id=aviso>Nenhum serviço encontrado</h2>";
}