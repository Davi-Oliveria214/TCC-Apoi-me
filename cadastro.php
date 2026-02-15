<?php
require("./includes/conexao.php");
include("./includes/cabecalho.php");
?>

<div class="div-form">
    <form action="./controls/cadastro.act.php" method="post" class="form">
        <h1>Cadastro</h1>
        <div class="box-login">
            <label for="idNome">Nome</label>
            <input type="text" name="nome" id="idNome" placeholder="Nome" required>
        </div>
        <div class="box-login">
            <label for="idEmail">Email</label>
            <input type="email" name="email" id="idEmail" placeholder="Email" required>
        </div>
        <div class="box-login">
            <label for="idSenha">Senha</label>
            <input type="password" name="senha" id="idSenha" placeholder="Senha" required>
        </div>
        <div class="box-login">
            <label for="idChave">Chave de acesso</label>
            <input type="text" name="chave" id="idChave" placeholder="Chave" required>
        </div>
        <div class="box-btn">
            <button type="submit" class="btn btn-login">Criar conta</button>
        </div>
    </form>
    <div class="box-btn">
        <a href="./login.php" class="btn-link">Logar</a>
        <a href="" class="btn-link">Criar conta para o condomínio </a>
    </div>
</div>
</main>

<img src="./img/banner.png" alt="" class="banner">
</div>