<?php
require("./includes/conexao.php");
include("./includes/cabecalho.php");
?>

<main class="login">
    <div>

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
                <button type="submit" class="btn btn-login">Logar</button>
                <a href="" class="btn btn-login">Criar conta</a>
            </div>
        </form>
    </div>

    <img src="./img/banner.png" alt="" class="img-login">
</main>