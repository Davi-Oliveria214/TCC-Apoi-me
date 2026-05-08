<?php
include("./includes/head.php");
include("./includes/topo.php");
session_start();
if (!isset($_SESSION['email_reset_aprovado'])) {
    $_SESSION["mensagem"] = "Erro para acessar a página de trocar senha.";
    header("Location: login.php");
    exit;
}
?>

<main class="autenticar">
    <div class="div-form">
        <form action="./controls/atualizar_senha.php" method="post" class="form ativar-load">
            <h1>Nova Senha</h1>
            <p>Defina sua nova senha para o e-mail: <b><?php echo $_SESSION['email_reset_aprovado']; ?></b></p>
            <div class="box-auth">
                <label for="idSenha">Nova Senha</label>
                <div class="input-container">
                    <input type="password" name="senha" id="idSenha" minlength="8" onkeydown="if(event.key === ' ') event.preventDefault()" oninput="verificarSenha()" placeholder="Senha" required>
                    <img src="./icon/visibility.png" class="olho-icon" alt="Mostrar senha" onclick="toggleSenha('idSenha', this)">
                </div>
                <p class="texto-senha" style="color: var(--verde-musgo-medio);"></p>
            </div>
            <div class="box-auth">
                <label for="idRptSenha">Repita a Nova Senha</label>
                <div class="input-container">
                    <input type="password" name="rptSenha" id="idRptSenha" minlength="8" onkeydown="if(event.key === ' ') event.preventDefault()" oninput="verificarSenha()" placeholder="Repita senha" required>
                    <img src="./icon/visibility.png" class="olho-icon" alt="Mostrar senha" onclick="toggleSenha('idRptSenha', this)">
                </div>
            </div>
            <div class="box-btn">
                <button type="submit" class="btn btn-auth">Salvar Nova Senha</button>
            </div>
        </form>
    </div>
</main>

<?php include("./includes/rodape.php"); ?>