<?php
include('./includes/head.php');
include('./includes/topo.php');
include('./util/avisos.php');
?>

<div class="div-auth">
    <main id="contato">
        <form action="./controls/feedback.php" method="post" class="fale-conosco ativar-load">
            <h1>Fale Conosco</h1>
            <div class="info-contato">
                <input type="text" name="nome" id="textNome" placeholder="Nome:" required value="<?php echo $nome ?? '' ?>">

                <input type="email" name="email" id="textEmail" placeholder="E-mail" required value="<?php echo $email ?? '' ?>">

                <textarea name="comentario" placeholder="Descreva seus elogíos, dúvidas, ajudas ou críticas:" id="comentarios" required style="resize: none;" maxlength="500"></textarea>

                <button type="submit" id="btn-enviar">Enviar</button>
            </div>
        </form>
    </main>

    <img src="./img/banner.png" alt="" class="banner">
</div>

<?php include("./includes/rodape.php"); ?>