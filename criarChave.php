<?php
require('./includes/conexao.php');
include('./includes/head.php');
include('./includes/topo.php');
include('./util/avisos.php');
?>

<main class="autenticar criar-chave">
        <form action="./controls/criarChave.act.php" method="post" class="form">
            <h1>Chave</h1>
            <div class="box-auth">
                <label for="idEmail">Email</label>
                <input type="email" name="email" id="idEmail" placeholder="Email" required>
            </div>
            <div class="box-auth">
                <label for="idSenha">Senha</label>
                <input type="password" name="senha" id="idSenha" placeholder="Senha" required>
            </div>
            <div class="box-btn">
                <button type="submit" class="btn btn-auth">Solicitar chave</button>
            </div>
        </form>
        <div class="box-btn">
            <a href="./login.php" class="btn-link">Login</a>
            <a href="./cadastro.php" class="btn-link">Cadastra</a>
        </div>
</main>