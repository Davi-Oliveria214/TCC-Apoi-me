<?php
include('./includes/head.php');
include('./includes/topo.php');
?>

<main class="autenticar">
    <div class="div-form">
        <form action="./controls/verificar.act.php" method="post" class="form">
            <h1>Verificar E-mail</h1>
            <p>Digite o código enviado para: <b><?php echo $_SESSION['email_verificar']; ?></b></p>
            
            <div class="box-auth">
                <label for="idCodigo">Código de 6 dígitos</label>
                <input type="text" name="codigo" id="idCodigo" maxlength="6" placeholder="000000" required>
            </div>

            <div class="box-btn">
                <button type="submit" class="btn btn-auth">Verificar</button>
            </div>
        </form>
    </div>
</main>