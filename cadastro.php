<?php
include('./includes/head.php');
include("./includes/topo.php");
include('./util/avisos.php');
?>

<div class="div-auth">
    <main class="autenticar">
        <div class="div-form">
            <form action="./controls/cadastro.act.php" method="post" class="form">
                <h1>Cadastro</h1>
                <div class="box-auth">
                    <label for="idNome">Nome</label>
                    <input type="text" name="nome" id="idNome" placeholder="Nome" required>
                </div>
                <div class="box-auth">
                    <label for="idEmail">Email</label>
                    <input type="email" name="email" id="idEmail" placeholder="Email" required>
                </div>
                <div class="box-auth">
                    <label for="idSenha">Senha</label>
                    <input type="password" name="senha" id="idSenha" placeholder="Senha" required>
                </div>
                <div class="box-auth">
                    <label for="idRptSenha">Repita senha</label>
                    <input type="password" name="rptSenha" id="idRptSenha" placeholder="Repita senha" required>
                </div>
                <div class="box-auth">
                    <label for="idChave">Chave de acesso</label>
                    <input type="text" name="chave" id="idChave" placeholder="Chave" required>
                </div>
                <div class="box-btn">
                    <button type="submit" class="btn btn-auth">Criar conta</button>
                </div>
            </form>
            <div class="box-btn">
                <a href="./login.php" class="btn-link">Logar</a>
                <a href="./criarChave.php" class="btn-link">Criar chave para o condomínio </a>
            </div>
        </div>
    </main>

    <img src="./img/banner.png" alt="" class="banner">
</div>