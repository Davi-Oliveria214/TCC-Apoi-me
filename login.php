<?php
require("./includes/conexao.php");
include("./includes/cabecalho.php");
?>

<main id="principal-logar-cadastro">
    <form action="" class="centro-cadastro-login">
        <h1>Faça seu Login</h1>

        <div class="card-login-cadastro">
            <div class="info-cadastro-login">
                <label for="idChave">Chave de acesso</label>
                <input type="text" id="idChave" placeholder="Chave de acesso" required>
            </div>

            <div class="info-cadastro-login">
                <label for="idEmail">Email</label>
                <input type="email" id="idEmail" placeholder="Email" required>
            </div>

            <div class="info-cadastro-login">
                <label for="idSenha">Senha</label>
                <input type="password" id="idSenha" placeholder="Senha" required>
            </div>
        </div>

        <div class="entrar-cadastrar">
            <button type="submit" class="botao btn-cadastrar">Logar</button>
            <button type="submit" class="botao btn-cadastrar">Cadastrar</button>
        </div>
    </form>
</main>

<?php
include "./includes/rodape.php";
?>