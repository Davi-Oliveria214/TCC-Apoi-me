<?php
require("./includes/conexao.php");
include("./includes/cabecalho.php");
?>

<div class="div-form">
    <form action="./controls/login.act.php" method="post" class="form">
        <h1>Login</h1>
        <div class="box-login">
            <label for="idEmail">Email</label>
            <input type="email" name="email" id="idEmail" placeholder="Email" required>
        </div>
        <div class="box-login">
            <label for="idSenha">Senha</label>
            <input type="password" name="senha" id="idSenha" placeholder="Senha" required>
        </div>
        <div class="box-btn">
            <button type="submit" class="btn btn-login">Entrar</button>
        </div>
    </form>

    <div class="box-btn">
        <a href="./cadastro.php" class="btn-link">Cadastrar</a>
        <a href="" class="btn-link">Criar conta para o condomínio </a>
    </div>
</div>


</main>
<img src="./img/banner.png" alt="" class="banner">