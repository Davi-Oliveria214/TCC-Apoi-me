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

        echo "<article id=card-servicos class=card-servicos data-categoria=$servico[id_categoria]>";
        echo "<img src=$servico[imagem] alt=Eletricista>";
        echo "<div class=card-conteudo>";
        echo "<div class=card-sobre>";
        echo "<h3>$servico[nome]</h3>";
        echo "<p>$servico[descricao]</p>";
        echo "</div>";
        echo "<data value=class=data-servico>Disponível: $horaInicio</data>";
        echo "<data value=class=data-servico>Disponível: $horaFim</data>";
        echo "<button class='btn botao-ver-mais' onclick=modelo(abrir)>Ver detalhes</button>";
        echo "</div>";
        echo "</article>";
    }
} else {
    echo "<h2 class=aviso>Nenhum serviço encontrado</h2>";
}
?>