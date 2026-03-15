<?php
require('./conexao.php');

$ids = isset($_GET['ignore']) ? $_GET['ignore'] : '';

$url = "servicos?select=*&id=not.in.($ids)&limit=1";
$servico = request($url, "GET");

if (empty($servico)) {
    $servico = request("servicos?select=*&limit=1", "GET");
}

if (!empty($servico)) {
    $s = $servico[0];
    $horaInicio = date('H:i', strtotime($s['horario_inicio']));
    $horaFim = date('H:i', strtotime($s['horario_fim']));
    $imagem = !empty($s['imagem']) ? $s['imagem'] : './img/default.jpg';

    echo "<div class='card card-servico' data-id='{$s['id']}'>";
    echo "<img src='$imagem' alt=''>";
    echo "<div>";
    echo "<div class='info-card'>";
    echo "<h2 class='titulo-card'>" . htmlspecialchars($s['nome']) . "</h2>";
    echo "<p>" . htmlspecialchars($s['descricao']) . "</p>";
    echo "<span>Horário: $horaInicio às $horaFim</span>";
    echo "</div>";
    echo "<div class='box-btn'>";
    echo "<a href='./controls/agendar.php?id=" . $s['id'] . "' class='btn'>Agendar serviço</a>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}