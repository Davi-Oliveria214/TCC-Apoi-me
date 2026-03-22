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
    <form action="./controls/agendar.php" method="post">
        <div class="info-servico">
            <p><?php echo $_SESSION['nomeServico'] ?></p>
        </div>

        <div class="img-servico">
            <img src="<?php echo $_SESSION['imgServico'] ?>" alt="">
        </div>

        <div class="input-group">
            <input type="date" required name="data">
        </div>

        <div class="input-group">
            <input type="time" required name="hora">
        </div>

        <div class="input-group">
            <input type="text" readonly name="categoria" value="<?php echo $_SESSION['nomeCategoria'] ?>">
        </div>

        <button type="submit">Contratar</button>
        <button type="button" onclick="window.location.href='index.php'">Cancelar</button>
    </form>
</main>