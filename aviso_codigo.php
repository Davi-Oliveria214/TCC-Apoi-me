<?php
include("./includes/head.php");
include("./includes/topo.php");
?>

<div class="div-auth">
    <main class="autenticar">
        <div class="recover-notice-card">
            <div class="notice-icon">
                <span>✉️</span>
            </div>

            <h1>Verifique seu E-mail</h1>
            <p class="notice-desc">
                Enviamos um código e um link de recuperação para o endereço cadastrado.
                <br>
                <strong style="color: var(--verde-musgo-medio);">Atenção: O link e o código expiram em 15 minutos.</strong>
            </p>

            <div class="steps-container">
                <div class="step">
                    <span class="step-number">1</span>
                    <p>Abra o e-mail enviado pelo <strong>Apoie-me</strong>.</p>
                </div>
                <div class="step">
                    <span class="step-number">2</span>
                    <p>Clique no botão <strong>Redefinir Senha</strong> antes do tempo acabar.</p>
                </div>
                <div class="step">
                    <span class="step-number">3</span>
                    <p>Insira o código na página e crie sua nova senha agora mesmo.</p>
                </div>
            </div>

            <div class="notice-footer">
                <p>O tempo expirou ou não recebeu o e-mail? Verifique sua caixa de spam.</p>
                <a href="esqueci_senha.php" class="btn-link">Solicitar novo envio</a>
                <hr class="notice-hr">
                <a href="login.php" class="btn-auth notice-btn">Voltar ao Login</a>
            </div>
        </div>
    </main>
    <img src="./img/banner.png" alt="" class="banner">
</div>