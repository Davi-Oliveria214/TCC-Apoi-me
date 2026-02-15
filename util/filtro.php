<?php
require("../includes/conexao.php");
$resp = $_POST['resp'];

$sql;
if ($resp == 0) {
    $sql = mysqli_query($con, "SELECT * FROM servicos");
} else {
    $sql = mysqli_query($con, "SELECT * FROM servicos WHERE id = $resp");
}

if ($sql->num_rows) {
    while ($servico = mysqli_fetch_assoc($sql)) {
        $horaInicio = date('H:i', strtotime($servico['horario_inicio']));
        $horaFim = date('H:i', strtotime($servico['horario_fim']));

        echo "<div class='card card-servico'>";
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
    echo "<h2 id=avisos>Nenhum serviço encontrado</h2>";
}
?>