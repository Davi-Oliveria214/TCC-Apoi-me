<?php
include("./includes/head.php");
include("./includes/topo.php");
session_start();
if (!isset($_SESSION['email_verificar'])) {
    header("Location: login.php");
    exit;
}
?>

<main class="autenticar">
    <div class="div-form">
        <form action="./controls/atualizar_senha.php" method="post" class="form">
            <h1>Nova Senha</h1>
            <p>Defina sua nova senha para o e-mail: <b><?php echo $_SESSION['email_verificar']; ?></b></p>
            <div class="box-auth">
                <label>Nova Senha</label>
                <input type="password" name="senha" placeholder="********" required>
            </div>
            <div class="box-auth">
                <label>Repita a Nova Senha</label>
                <input type="password" name="rpt_senha" placeholder="********" required>
            </div>
            <div class="box-btn">
                <button type="submit" class="btn btn-auth">Salvar Nova Senha</button>
            </div>
        </form>
    </div>
</main>