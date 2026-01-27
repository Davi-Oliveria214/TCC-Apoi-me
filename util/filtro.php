<?php
require("../includes/conexao.php");
$resp = $_POST['resp'];

$sql;
if ($resp == 0) {
    $sql = mysqli_query($con, "SELECT s.nome AS nome, s.imagem AS imagem, s.descricao AS descricao, s.data_inicio AS inicio, s.data_fim AS fim, c.id AS id_categoria, c.nome AS nome_categoria FROM oferecidos o JOIN servicos s ON o.id_servico=s.id JOIN categorias c ON o.id_categoria = c.id");
} else {
    $sql = mysqli_query($con, "SELECT s.nome AS nome, s.imagem AS imagem, s.descricao AS descricao, s.data_inicio AS inicio, s.data_fim AS fim, c.id AS id_categoria, c.nome AS nome_categoria FROM oferecidos o JOIN servicos s ON o.id_servico=s.id JOIN categorias c ON o.id_categoria = c.id WHERE c.id = $resp");
}

if ($sql->num_rows) {
    while ($servico = mysqli_fetch_assoc($sql)) {
        echo "<article id=card-servicos class=card-servicos data-categoria=$servico[id_categoria]>";
        echo "<img src=$servico[imagem] alt=Eletricista>";
        echo "<div class=card-conteudo>";
        echo "<div class=card-sobre>";
        echo "<h3>$servico[nome]</h3>";
        echo "<p>$servico[descricao]</p>";
        echo "</div>";
        echo "<data value=class=data-servico>Disponível: $servico[inicio]</data>";
        echo "<data value=class=data-servico>Disponível: $servico[fim]</data>";
        echo "<button class=botao-ver-mais onclick=modelo(abrir)>Ver detalhes</button>";
        echo "</div>";
        echo "</article>";
    }
} else {
    echo "<h2 class=aviso>Nenhum serviço encontrado</h2>";
}
?>