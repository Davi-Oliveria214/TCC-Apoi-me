<?php
include("./includes/head.php");
include("./includes/topo.php"); 
include('./util/avisos.php');
?>

<div class="div-auth">
    <main class="autenticar">
        <div class="div-form">
            <form action="./controls/nova_senha.php" method="post" class="form">
                <h1>Esqueci senha</h1>
                <div class="box-auth">
                    <label for="idEmail">Email</label>
                    <input type="email" name="email" id="idEmail" placeholder="Email" required>
                </div>
                <div class="box-auth">
                    <label for="idNovaSenha">Nova senha</label>
                    <input type="password" name="senha" id="idNovaSenha" placeholder="Senha" required>
                </div>
                <div class="box-auth">
                    <label for="idRptSenha">Repita a nova senha</label>
                    <input type="password" name="rptSenha" id="idRptSenha" placeholder="Senha" required>
                </div>
                <div class="box-btn" style="flex-direction: row;">
                    <button type="submit" class="btn btn-auth">Trocar senha</button>
                </div>
            </form>

            <div class="box-btn">
                <a href="./login.php" class="btn-link">Logar</a>
                <a href="./cadastro.php" class="btn-link">Cadastrar</a>
                <a href="./criarChave.php" class="btn-link">Criar chave para o condomínio </a>
            </div>
        </div>
    </main>

    <img src="./img/banner.png" alt="" class="banner">
</div>