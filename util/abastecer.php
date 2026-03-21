<?php
require('./conexao.php');

$servico = request("servicos?select=*&limit=1", "GET");

if (!empty($servico)) :
    $s = $servico[0];

    $horaInicio = $s['hora_inicio'] 
        ? date('H:i', strtotime($s['hora_inicio'])) 
        : "Não informado";

    $imagem = !empty($s['imagem']) ? $s['imagem'] : './img/default.jpg';
?>

<div class='card card-servico'>
    <img src='<?php echo $imagem ?>' alt=''>
    <div>
        <div class='info-card'>
            <h2 class='titulo-card'><?php echo $s['nome'] ?></h2>
            <p><?php echo $s['descricao'] ?></p>
            <span><?php echo $horaInicio ?></span>
        </div>
        <div class='box-btn'>
            <a href='./controls/agendar.php?id=<?php echo $s["id"] ?>' class='btn'>
                Agendar serviço
            </a>
        </div>
    </div>
</div>

<?php endif; ?> 