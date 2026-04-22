<?php
include("./includes/head.php");
include("./includes/topo.php");
?>

<div class="div-auth">
    <main class="autenticar">
        <div class="div-form">
            <form action="./controls/login.act.php" method="post" class="form ativar-load">
                <h1>Login</h1>
                <div class="box-auth">
                    <label for="idChave">Chave de acesso</label>
                    <input type="text" name="chave" id="idChave" placeholder="Chave" required>
                </div>
                <div class="box-auth">
                    <label for="idEmail">Email</label>
                    <input type="email" name="email" id="idEmail" placeholder="Email" required>
                </div>
                <div class="box-auth">
                    <label for="idSenha">Senha</label>
                    <input type="password" name="senha" id="idSenha" placeholder="Senha" required>
                </div>
                <div class="box-btn">
                    <a href="./enviar_codigo.php?tipo_envio=redefinir" class="btn-link">Esqueci a senha</a>
                    <button type="submit" class="btn btn-auth">Entrar</button>
                    <a href="./cadastro.php" class="btn-auth">Cadastrar</a>
                </div>
            </form>
        </div>
    </main>

    <img src="./img/banner.png" alt="" class="banner">
</div>

<?php include("./includes/rodape.php"); ?>