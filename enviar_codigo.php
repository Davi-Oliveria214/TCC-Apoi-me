<?php
include("./includes/head.php");
include("./includes/topo.php");
include('./util/avisos.php');

$tipo = $_GET['tipo_envio'] ?? '';
?>

<div class="div-auth">
    <main class="autenticar">
        <div class="recover-container">
            <div class="recover-card">
                <h1 class="recover-title">Enviar código</h1>
                <p class="recover-subtitle">Insira seu email. Um código de redefinição será enviado.</p>
                <form action="./controls/enviar_recuperacao.act.php" method="post" class="recover-form">
                    <input type="email" name="email" placeholder="Digite seu email" class="recover-input" required>
                    <select name="categoria" id="" required>
                        <option value="" disabled <?php echo empty($tipo) ? 'selected' : ''; ?> hidden>Tipo do código</option>

                        <option value="redefinir" <?php echo ($tipo === 'redefinir') ? 'selected' : ''; ?>>
                            Redefinir senha
                        </option>

                        <option value="validar" <?php echo ($tipo === 'validar') ? 'selected' : ''; ?>>
                            Validar email
                        </option>
                    </select>
                    <button type="submit" class="recover-button">Continuar</button>
                </form>
            </div>
        </div>
    </main>
    <img src="./img/banner.png" alt="" class="banner">
</div>