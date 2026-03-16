<?php
include('./includes/head.php');
include('./includes/topo.php');
include('./util/avisos.php');
?>

<div class="div-auth">
    <main id="contato">
        <form action="./controls/feedback.php" method="post" class="fale-conosco">
            <h1>Fale Conosco</h1>
            <div class="info-contato">
                <input type="text" name="nome" id="textNome" placeholder="Nome:" required value="<?php echo $nome ?? '' ?>">

                <input type="email" name="email" id="textEmail" placeholder="E-mail" required value="<?php echo $email ?? '' ?>">

                <input type="tel" name="telefone" id="textTel" placeholder="Telefone:" required>

                <textarea name="comentario" placeholder="Comentários:" id="comentarios" required style="resize: none;" maxlength="500"></textarea>

                <label for="idNota">Avaliação do Sistema</label>
                <select name="nota" id="idNota" class="feedback-opcao" required>
                    <option value="" disabled selected>Selecione uma nota</option>
                    <option value="1">1 - Muito Ruim</option>
                    <option value="2">2 - Ruim</option>
                    <option value="3">3 - Regular</option>
                    <option value="4">4 - Bom</option>
                    <option value="5">5 - Excelente</option>
                </select>

                <button type="submit" id="btn-enviar">Enviar</button>
            </div>
        </form>
    </main>

    <img src="./img/banner.png" alt="" class="banner">
</div>