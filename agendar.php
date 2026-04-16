<?php
session_start();
include('./includes/head.php');
include('./includes/topo.php');

$idServico = intval($_GET['id']);

$servico = request(
    "servicos?select=id,nome,descricao,imagem,id_prestador,categoria,codigo,categorias(id,nome)&id=eq.$idServico",
    "GET"
);

if (empty($servico)) {
    echo "Serviço não encontrado";
    exit;
}

$servico = $servico[0];

$categoria = $servico['categorias'];
if (isset($categoria[0])) {
    $categoria = $categoria[0];
}

$_SESSION['idServico'] = $servico['id'];
$_SESSION['nomeServico'] = $servico['nome'];
$_SESSION['idPrestador'] = $servico['id_prestador'];
$_SESSION['categoria'] = $servico['categoria'];
$_SESSION['codigo'] = $servico['codigo'];
$_SESSION['imgServico'] = $servico['imagem'];
$_SESSION['idCategoria'] = $categoria['id'];
$_SESSION['nomeCategoria'] = $categoria['nome'];
?>

<main class="main-agendar">
    <form action="./controls/agendar.act.php" method="post">
        <div class="info-servico">
            <p><?php echo $_SESSION['nomeServico'] ?></p>
        </div>

        <div class="img-servico">
            <img src="<?php echo $_SESSION['imgServico'] ?>" alt="Imagem do serviço">
        </div>

        <div class="input-group">
            <label>Data do Atendimento</label>
            <input type="date" required name="data">
        </div>

        <div class="input-group">
            <label>Horário</label>
            <input type="time" required name="hora">
        </div>

        <div class="input-group">
            <label>Categoria</label>
            <input type="text" readonly name="categoria" value="<?php echo $_SESSION['nomeCategoria'] ?>">
        </div>

        <div class="input-group">
            <label>Observações</label>
            <textarea name="observacao" maxlength="300" placeholder="Ex: Portão social está com defeito, favor interfonar..."></textarea>
        </div>

        <div class="box-btn" style="display: flex; flex-direction: column; padding: 10px 0;">
            <button type="submit">Confirmar Agendamento</button>
            <button type="button" onclick="window.location.href='./servicos.php'">Voltar</button>
        </div>
    </form>
</main>